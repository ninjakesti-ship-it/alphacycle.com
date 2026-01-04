<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

include 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$table_name  = $_POST['table_name'] ?? '';
$name        = trim($_POST['name'] ?? '');
$price       = $_POST['price'] ?? '';
$stock       = $_POST['stock'] ?? '';
$description = $_POST['description'] ?? '';
$image_name  = null;

$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing"];

if ($table_name === '' || $name === '' || !is_numeric($price) || !is_numeric($stock)) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid product details.']);
    exit;
}
if (!in_array($table_name, $allowed_tables, true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid category selected.']);
    exit;
}

// Ensure table exists
$checkStmt = $conn->prepare("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
$checkStmt->bind_param('s', $table_name);
$checkStmt->execute(); $checkStmt->store_result();
if ($checkStmt->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => "Category table '$table_name' does not exist."]);
    $checkStmt->close(); exit;
}
$checkStmt->close();

// Optional image upload
if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $original_filename = basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

    $check = @getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(['success' => false, 'message' => 'File is not an image.']);
        exit;
    }
    if ($_FILES["image"]["size"] > 5_000_000) {
        echo json_encode(['success' => false, 'message' => 'Image file too large (max 5MB).']);
        exit;
    }
    if (!in_array($imageFileType, ['jpg','jpeg','png','gif','webp'], true)) {
    echo json_encode(['success' => false, 'message' => 'Only JPG, JPEG, PNG, GIF & WEBP allowed.']);
    exit;
}


    $unique_filename = uniqid('', true) . '.' . $imageFileType;
    $target_file = $target_dir . $unique_filename;
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo json_encode(['success' => false, 'message' => 'Error uploading image.']);
        exit;
    }
    $image_name = $unique_filename;
}

// Insert product
$sql = "INSERT INTO `$table_name` (name, price, stock, description, image) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Database prepare error: ' . $conn->error]);
    exit;
}

$price = (float)$price;
$stock = (int)$stock;
$stmt->bind_param("sdiss", $name, $price, $stock, $description, $image_name);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Product added successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add product: ' . $stmt->error]);
}

$stmt->close();
$conn->close();