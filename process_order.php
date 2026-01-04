<?php
session_start();
include 'db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing"];

$input = json_decode(file_get_contents('php://input'), true);
$order_items   = $input['items'] ?? [];
$customer_info = $input['customer'] ?? [];

if (empty($order_items)) {
    echo json_encode(['success' => false, 'message' => 'No items in order']);
    exit;
}

$conn->begin_transaction();

try {
    $total_amount = 0.0;
    $order_id = uniqid('ORD_');

    // Validate stock and compute total
    foreach ($order_items as $item) {
        $product_id = (int)($item['product_id'] ?? 0);
        $quantity   = (int)($item['quantity'] ?? 0);
        $table      = (string)($item['table'] ?? '');

        if ($product_id <= 0 || $quantity <= 0 || !in_array($table, $allowed_tables, true)) {
            throw new Exception("Invalid item in order.");
        }

        $stock_query = "SELECT stock, name, price FROM `$table` WHERE id = ?";
        $stmt = $conn->prepare($stock_query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_assoc();
        $stmt->close();

        if (!$product) {
            throw new Exception("Product not found: ID $product_id");
        }
        if ((int)$product['stock'] < $quantity) {
            throw new Exception("Insufficient stock for {$product['name']}. Available: {$product['stock']}, Requested: $quantity");
        }
        $total_amount += ((float)$product['price'] * $quantity);
    }

    // Create order record
    $order_query = "INSERT INTO `orders` (order_id, customer_name, customer_email, customer_phone, customer_address, total_amount, order_date, status)
                    VALUES (?, ?, ?, ?, ?, ?, NOW(), 'confirmed')";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param(
        'sssssd',
        $order_id,
        $customer_info['name'],
        $customer_info['email'],
        $customer_info['phone'],
        $customer_info['address'],
        $total_amount
    );
    $stmt->execute();
    $stmt->close();

    // Insert items and reduce stock
    foreach ($order_items as $item) {
        $product_id = (int)$item['product_id'];
        $quantity   = (int)$item['quantity'];
        $table      = (string)$item['table'];

        $product_query = "SELECT name, price FROM `$table` WHERE id = ?";
        $stmt = $conn->prepare($product_query);
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_assoc();
        $stmt->close();

        if (!$product) {
            throw new Exception("Product not found during item insert: $product_id");
        }

        $item_query = "INSERT INTO `order_items` (order_id, product_id, product_table, product_name, quantity, price, subtotal)
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $subtotal = (float)$product['price'] * $quantity;
        $stmt = $conn->prepare($item_query);
        // Types: s i s s i d d
        $stmt->bind_param('sissidd', $order_id, $product_id, $table, $product['name'], $quantity, $product['price'], $subtotal);
        $stmt->execute();
        $stmt->close();

        $update_stock_query = "UPDATE `$table` SET stock = stock - ? WHERE id = ?";
        $stmt = $conn->prepare($update_stock_query);
        $stmt->bind_param('ii', $quantity, $product_id);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $stmt->close();
            throw new Exception("Failed to update stock for product ID: $product_id");
        }
        $stmt->close();
    }

    unset($_SESSION['cart']);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully!',
        'order_id' => $order_id,
        'total_amount' => $total_amount
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();