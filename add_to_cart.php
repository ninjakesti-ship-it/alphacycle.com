<?php
session_start();
include 'db_connect.php';

// Get product ID, table, and quantity from form
$product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : null;
$table      = isset($_POST['table']) ? $_POST['table'] : null;
$quantity   = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

// Validate inputs
if (!$product_id || !$table) {
    die("Invalid product or table.");
}

// Allowed tables
$allowed_tables = ['clothing', 'mountain', 'road', 'gravel', 'ebikes', 'ch', 'accessories', 'kids', 'top_products'];
if (!in_array($table, $allowed_tables, true)) {
    die("Invalid table name.");
}

// Fetch product from DB
$sql = "SELECT id, name, price, stock FROM `$table` WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL error: " . $conn->error);
}
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// Initialize cart as array of items
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product already in cart
$found = false;
foreach ($_SESSION['cart'] as &$item) {
    if ($item['product_id'] == $product_id && $item['table'] == $table) {
        $item['quantity'] += $quantity;
        // Prevent adding more than stock
        if ($item['quantity'] > $product['stock']) {
            $item['quantity'] = $product['stock'];
        }
        $found = true;
        break;
    }
}
unset($item); // good practice when using references

// If not found, add new item
if (!$found) {
    $_SESSION['cart'][] = [
        'product_id' => $product['id'],
        'table'      => $table,
        'quantity'   => min($quantity, $product['stock'])
    ];
}

// Redirect to cart page
header("Location: cart2.php");
exit;
