<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : null;
$target_dir = "uploads/";

// Ensure uploads directory exists
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
}

if (!isset($_FILES["product_image"]) || $_FILES["product_image"]["error"] !== 0) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or an upload error occurred. Error code: ' . ($_FILES["product_image"]["error"] ?? 'n/a')]);
    exit;
}

$target_file = $target_dir . basename($_FILES["product_image"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$check = @getimagesize($_FILES["product_image"]["tmp_name"]);
if ($check === false) {
    echo json_encode(['success' => false, 'message' => 'File is not an image.']);
    exit;
}

if ($_FILES["product_image"]["size"] > 5_000_000) {
    echo json_encode(['success' => false, 'message' => 'Sorry, your file is too large (max 5MB).']);
    exit;
}

if (!in_array($imageFileType, ['jpg','jpeg','png','gif'], true)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.']);
    exit;
}

if (!move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
    echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
    exit;
}

$image_name = basename($_FILES["product_image"]["name"]);

if ($product_id) {
    // Update only mountain table here; modify as needed
    $stmt = $conn->prepare("UPDATE `mountain` SET image = ? WHERE id = ?");
    $stmt->bind_param("si", $image_name, $product_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'File uploaded and database updated.', 'image_name' => $image_name]);
    } else {
        echo json_encode(['success' => false, 'message' => 'File uploaded, but failed to update database: ' . $conn->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => true, 'message' => 'File uploaded.', 'image_name' => $image_name]);
}

$conn->close();