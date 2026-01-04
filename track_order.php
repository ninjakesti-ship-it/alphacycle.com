<?php
session_start();
include 'db_connect.php';

// Check for order_id in the URL
if (!isset($_GET['order_id'])) {
    echo "<p style='text-align: center; font-family: Inter, sans-serif; color: #e0e0e0; background: #1a1c22; padding: 2rem; border-radius: 8px; margin: 2rem;'>No Order ID provided.</p>";
    exit();
}

$order_id = $_GET['order_id'];

// --- Fetch order details from the database ---
$order_sql = "SELECT order_id, status, order_date FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
$stmt->close();

if (!$order) {
    // Handle case where order is not found
    echo "<p style='text-align: center; font-family: Inter, sans-serif; color: #e0e0e0; background: #1a1c22; padding: 2rem; border-radius: 8px; margin: 2rem;'>Order #<?= htmlspecialchars($order_id) ?> not found.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order #<?= htmlspecialchars($order_id) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", sans-serif;
            background-color: #0d0e12;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        .tracking-card {
            max-width: 600px;
            width: 100%;
            background-color: #1a1c22;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            padding: 3rem;
            text-align: center;
            border: 1px solid #3a3d45;
        }
        .status-container {
            margin: 2rem 0;
        }
        .status-header {
            font-size: 2.5rem;
            font-weight: 700;
            color: #00ffff;
            margin-bottom: 1rem;
        }
        .status-text {
            font-size: 1.5rem;
            font-weight: 500;
            color: #00ffaa;
            text-transform: capitalize;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: #262930;
            border-radius: 8px;
            margin-top: 2rem;
        }
        .info-item {
            text-align: left;
        }
        .info-label {
            font-size: 0.9rem;
            color: #a0a0a0;
        }
        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .btn {
            background-color: #00ffaa;
            color: #0d0e12;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 2rem;
        }
        .btn:hover { background-color: #00c98d; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="tracking-card">
        <h1 class="text-3xl font-bold text-white mb-2">Track Your Order</h1>
        <p class="text-xl text-gray-400">Order ID: <span class="text-white font-semibold"><?= htmlspecialchars($order['order_id']) ?></span></p>
        
        <div class="status-container">
            <div class="status-header">Order Status</div>
            <div class="status-text"><?= htmlspecialchars($order['status']) ?></div>
        </div>

        <div class="order-info">
            <div class="info-item">
                <div class="info-label">Order Date</div>
                <div class="info-value"><?= htmlspecialchars(date('d M Y', strtotime($order['order_date']))) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Order Time</div>
                <div class="info-value"><?= htmlspecialchars(date('H:i A', strtotime($order['order_date']))) ?></div>
            </div>
        </div>

        <a href="user_orders.php" class="btn">Return to My Orders</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>
