<?php
session_start();

if (!isset($_POST['product_id']) || !isset($_POST['table'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}

$product_id = (int) $_POST['product_id'];
$table = $_POST['table'];

// Make sure cart exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

foreach ($_SESSION['cart'] as $key => $item) {
    if ((int)$item['product_id'] === $product_id && $item['table'] === $table) {
        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
        break;
    }
}

echo json_encode(['status' => 'success']);
