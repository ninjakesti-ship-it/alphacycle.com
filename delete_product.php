<?php
include 'db_connect.php';

$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing"];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_stocks.php?msg=' . urlencode('Invalid request method.'));
    exit;
}

$id    = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$table = $_POST['table'] ?? '';

if ($id <= 0 || !in_array($table, $allowed_tables, true)) {
    header('Location: manage_stocks.php?msg=' . urlencode('Invalid input.'));
    exit;
}

// Get image name to optionally delete file
$img = null;
$stmt = $conn->prepare("SELECT image FROM `$table` WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) $img = $row['image'] ?? null;
$stmt->close();

// Delete product
$stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$deleted = $stmt->affected_rows > 0;
$stmt->close();

// Optionally remove file (only within uploads)
if ($deleted && $img && is_string($img)) {
    $path = __DIR__ . '/uploads/' . basename($img);
    if (is_file($path)) @unlink($path);
}

$conn->close();
header('Location: manage_stocks.php?msg=' . urlencode($deleted ? 'Product deleted.' : 'Delete failed.'));