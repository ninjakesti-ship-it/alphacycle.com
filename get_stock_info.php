<?php
include 'db_connect.php';
header('Content-Type: application/json');

$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing"];

if (!isset($_GET['product_id'], $_GET['table'])) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$product_id = (int)$_GET['product_id'];
$table = $_GET['table'];

if (!in_array($table, $allowed_tables, true)) {
    echo json_encode(['success' => false, 'error' => 'Invalid table']);
    exit;
}

$query = "SELECT stock, name FROM `$table` WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();

if ($product) {
    echo json_encode(['success' => true, 'stock' => (int)$product['stock'], 'name' => $product['name']]);
} else {
    echo json_encode(['success' => false, 'error' => 'Product not found']);
}

$stmt->close();
$conn->close();