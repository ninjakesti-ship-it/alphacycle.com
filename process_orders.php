<?php
/**
 * new_process_orders.php
 * Tailored for mountain: updates stock after successful checkout and provides real-time stock snapshots.
 *
 * Usage:
 * 1) Real-time stock snapshot (GET):
 *    GET new_process_orders.php?action=stock&ids=1,2,3
 *    or without ids, it will read mountain items from $_SESSION['cart'].
 *
 * 2) Reduce stock after a successful checkout (POST):
 *    - By order_id (reads mountain items from order_items):
 *      POST JSON: { "order_id": "ORD_..." }
 *
 *    - Or by explicit items:
 *      POST JSON: { "items": [{ "product_id": 1, "quantity": 2 }] }
 *
 * Response: JSON { success: bool, message?: string, updated_stocks?: { [productId]: remainingStock }, cart_count?: number }
 */

session_start();
include 'db_connect.php';
header('Content-Type: application/json');

// Helpers
function json_fail($message, $code = 400) {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

function is_json_request(): bool {
    $ct = $_SERVER['CONTENT_TYPE'] ?? '';
    return stripos($ct, 'application/json') !== false;
}

// GET: Stock snapshot (real-time)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'stock') {
    $idsParam = trim($_GET['ids'] ?? '');
    $ids = [];

    if ($idsParam !== '') {
        foreach (explode(',', $idsParam) as $id) {
            $id = (int)$id;
            if ($id > 0) $ids[] = $id;
        }
    } else {
        // Fallback: read mountain IDs from session cart
        $cart = $_SESSION['cart'] ?? [];
        foreach ($cart as $item) {
            if (($item['table'] ?? '') === 'mountain') {
                $pid = (int)($item['product_id'] ?? 0);
                if ($pid > 0) $ids[] = $pid;
            }
        }
        $ids = array_values(array_unique($ids));
    }

    if (empty($ids)) {
        echo json_encode(['success' => true, 'stocks' => []]);
        exit;
    }

    // Build IN clause safely
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("SELECT id, stock FROM `mountain` WHERE id IN ($placeholders)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();

    $stocks = [];
    while ($row = $res->fetch_assoc()) {
        $stocks[(int)$row['id']] = (int)$row['stock'];
    }
    $stmt->close();

    echo json_encode(['success' => true, 'stocks' => $stocks]);
    exit;
}

// POST: Reduce stock for mountain after checkout
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_fail('Invalid request method', 405);
}

// Read input (JSON or form)
$input = [];
if (is_json_request()) {
    $raw = file_get_contents('php://input');
    $input = json_decode($raw, true) ?? [];
} else {
    $input = $_POST;
}

$order_id = trim((string)($input['order_id'] ?? ''));
$itemsNormalized = [];

// Option A: derive items from order_id (order_items)
if ($order_id !== '') {
    $stmt = $conn->prepare("SELECT product_id, quantity FROM `order_items` WHERE order_id = ? AND product_table = 'mountain'");
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $pid = (int)$row['product_id'];
        $qty = (int)$row['quantity'];
        if ($pid > 0 && $qty > 0) {
            $itemsNormalized[] = ['product_id' => $pid, 'quantity' => $qty];
        }
    }
    $stmt->close();
}

// Option B: accept explicit items payload
if (empty($itemsNormalized) && !empty($input['items']) && is_array($input['items'])) {
    foreach ($input['items'] as $it) {
        $pid = (int)($it['product_id'] ?? 0);
        $qty = (int)($it['quantity'] ?? 0);
        if ($pid > 0 && $qty > 0) {
            $itemsNormalized[] = ['product_id' => $pid, 'quantity' => $qty];
        }
    }
}

if (empty($itemsNormalized)) {
    json_fail('No valid mountain items provided for stock update.');
}

// Start transaction and lock rows to avoid race conditions
$conn->begin_transaction();

try {
    $updated = [];
    foreach ($itemsNormalized as $it) {
        $pid = (int)$it['product_id'];
        $qty = (int)$it['quantity'];

        // Lock row
        $stmt = $conn->prepare("SELECT stock, name, price FROM `mountain` WHERE id = ? FOR UPDATE");
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $res = $stmt->get_result();
        $prod = $res->fetch_assoc();
        $stmt->close();

        if (!$prod) {
            throw new Exception("Accessory not found: ID {$pid}");
        }

        $stock = (int)$prod['stock'];
        if ($stock < $qty) {
            throw new Exception("Insufficient stock for {$prod['name']} (Available: {$stock}, Requested: {$qty})");
        }

        // Decrement stock
        $stmt = $conn->prepare("UPDATE `mountain` SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param('ii', $qty, $pid);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            $stmt->close();
            throw new Exception("Failed to update stock for accessory ID: {$pid}");
        }
        $stmt->close();

        // Fetch fresh stock
        $stmt = $conn->prepare("SELECT stock FROM `mountain` WHERE id = ?");
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        $updated[$pid] = (int)$row['stock'];
    }

    // Update session cart: reduce or remove mountain that were just checked out
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $idx => $cartItem) {
            if (($cartItem['table'] ?? '') !== 'mountain') {
                continue;
            }
            $cpid = (int)($cartItem['product_id'] ?? 0);
            foreach ($itemsNormalized as $it) {
                if ($cpid === (int)$it['product_id']) {
                    $newQty = max(0, ((int)$cartItem['quantity']) - (int)$it['quantity']);
                    if ($newQty <= 0) {
                        unset($_SESSION['cart'][$idx]);
                    } else {
                        $_SESSION['cart'][$idx]['quantity'] = $newQty;
                    }
                }
            }
        }
        // Reindex the cart array
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    $conn->commit();

    echo json_encode([
        'success'        => true,
        'message'        => 'mountain stock updated successfully.',
        'order_id'       => $order_id ?: null,
        'updated_stocks' => $updated,
        'cart_count'     => isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0
    ]);
} catch (Exception $e) {
    $conn->rollback();
    json_fail($e->getMessage(), 400);
}

$conn->close();