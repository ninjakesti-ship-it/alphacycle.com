<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

// --- Security Check: Ensure only an admin can access this page ---
// For this example, we'll use a placeholder check. In a real-world app,
// you would have a robust authentication system with user roles.
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] !== true) {
    // This is a basic example; you might redirect to a login page or show an error
    echo "<p style='text-align: center; font-family: Inter, sans-serif; color: #e0e0e0; background: #1a1c22; padding: 2rem; border-radius: 8px; margin: 2rem;'>Access Denied. You must be an administrator to view this page.</p>";
    exit();
}

// --- Handle Order Status Update (if a POST request is made) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ss", $status, $order_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Status for order #$order_id updated to $status."]);
    } else {
        echo json_encode(['success' => false, 'message' => "Failed to update status for order #$order_id."]);
    }
    $stmt->close();
    exit(); // Stop script execution after handling the POST request
}

// --- Fetch all orders for display ---
$sql = "SELECT order_id, customer_name, total_amount, order_date, status FROM orders ORDER BY id DESC";
$result = $conn->query($sql);
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", sans-serif;
            background-color: #0d0e12;
            color: #e0e0e0;
            min-height: 100vh;
            padding: 2rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #1a1c22;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            padding: 2rem;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }
        .orders-table th, .orders-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #3a3d45;
        }
        .orders-table thead th {
            background-color: #262930;
            color: #e0e0e0;
            font-weight: 600;
        }
        .orders-table tbody tr:hover {
            background-color: #30333b;
        }
        .status-select {
            background-color: #262930;
            border: 1px solid #3a3d45;
            color: #e0e0e0;
            padding: 0.5rem;
            border-radius: 4px;
        }
        .status-btn {
            background-color: #00ffaa;
            color: #0d0e12;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        .status-btn:hover {
            background-color: #00c98d;
            transform: translateY(-1px);
        }
        .message-box {
            position: fixed;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: #00ffaa;
            color: #0d0e12;
            padding: 1rem 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 100;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .message-box.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-3xl font-bold text-white mb-6">Admin Dashboard: Manage Orders</h1>

        <div id="message-box" class="message-box"></div>

        <div class="overflow-x-auto">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['order_id']) ?></td>
                                <td><?= htmlspecialchars($order['customer_name']) ?></td>
                                <td>₹<?= number_format((float)$order['total_amount'], 2) ?></td>
                                <td><?= htmlspecialchars(date('d M Y', strtotime($order['order_date']))) ?></td>
                                <td>
                                    <form class="status-form">
                                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']) ?>">
                                        <select name="status" class="status-select">
                                            <option value="pending" <?= ($order['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= ($order['status'] === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="shipped" <?= ($order['status'] === 'shipped') ? 'selected' : '' ?>>Shipped</option>
                                            <option value="delivered" <?= ($order['status'] === 'delivered') ? 'selected' : '' ?>>Delivered</option>
                                            <option value="cancelled" <?= ($order['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                        </select>
                                </td>
                                <td>
                                    <button type="submit" class="status-btn">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('.status-form');
            const messageBox = document.getElementById('message-box');

            forms.forEach(form => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const formData = new FormData(form);
                    const orderId = formData.get('order_id');
                    const newStatus = formData.get('status');

                    try {
                        const response = await fetch(window.location.href, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        const result = await response.json();

                        if (result.success) {
                            showMessage(result.message, 'success');
                            // You could also visually update the row here if needed
                        } else {
                            showMessage(result.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showMessage('An error occurred. Please try again.', 'error');
                    }
                });
            });

            function showMessage(text, type) {
                messageBox.textContent = text;
                messageBox.style.backgroundColor = (type === 'success') ? '#00ffaa' : '#ff4d4d';
                messageBox.classList.add('show');

                setTimeout(() => {
                    messageBox.classList.remove('show');
                }, 3000);
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
