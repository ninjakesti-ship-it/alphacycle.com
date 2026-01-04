<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db_connect.php';

// --- Security Check: Ensure only an admin can access this page ---
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] !== true) {
    echo "<p style='text-align: center; font-family: Inter, sans-serif; color: #e0e0e0; background: #1a1c22; padding: 2rem; border-radius: 8px; margin: 2rem;'>Access Denied. You must be an administrator to view this page.</p>";
    exit();
}

// Fetch all order items, ordered by order_id, along with customer info
$sql = "SELECT oi.*, o.customer_name, o.customer_email, o.order_date FROM order_items oi INNER JOIN orders o ON oi.order_id = o.order_id ORDER BY oi.order_id DESC";
$result = $conn->query($sql);

$current_order = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Order Items</title>
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
        .order-group {
            background: #262930;
            margin-bottom: 2rem;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 6px solid #00ffaa;
        }
        .order-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .order-group h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #00ffaa;
        }
        .order-group .order-details {
            color: #a0a0a0;
            font-size: 0.875rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .order-group table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .order-group th, .order-group td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #3a3d45;
        }
        .order-group th {
            background: #34373d;
            color: #e0e0e0;
            text-transform: uppercase;
            font-weight: 600;
        }
        .order-group tr:last-child td {
            border-bottom: none;
        }
        .logout-btn {
            background-color: #ff4d4d;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s;
            font-weight: 600;
        }
        .logout-btn:hover {
            background-color: #e03f3f;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">All Ordered Items</h1>
            <a href="admin_login.php?logout=1" class="logout-btn">Logout</a>
        </div>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Check if this is a new order group
                if ($row['order_id'] !== $current_order) {
                    if ($current_order !== '') {
                        echo "</tbody></table></div>"; // Close previous group
                    }
                    $current_order = $row['order_id'];
                    echo "<div class='order-group'>";
                    echo "<div class='order-group-header'>";
                    echo "<h3>Order ID: " . htmlspecialchars($current_order) . "</h3>";
                    echo "<div class='order-details'>";
                    echo "<span>Customer: " . htmlspecialchars($row['customer_name']) . "</span>";
                    echo "<span>Email: " . htmlspecialchars($row['customer_email']) . "</span>";
                    echo "<span>Date: " . htmlspecialchars(date('F j, Y, g:i a', strtotime($row['order_date']))) . "</span>";
                    echo "</div>";
                    echo "</div>";
                    echo "<table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price (₹)</th>
                                <th>Qty</th>
                                <th>GST (18%)</th>
                                <th>Total (Incl. GST)</th>
                            </tr>
                        </thead>
                        <tbody>";
                }

                // Calculate GST and total
                $subtotal = $row['price'] * $row['quantity'];
                $gst = $subtotal * 0.18;
                $total_with_gst = $subtotal + $gst;

                echo "<tr>
                    <td>" . htmlspecialchars($row['product_name']) . "</td>
                    <td>₹" . number_format($row['price'], 2) . "</td>
                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                    <td>₹" . number_format($gst, 2) . "</td>
                    <td>₹" . number_format($total_with_gst, 2) . "</td>
                </tr>";
            }
            echo "</tbody></table></div>"; // Close the final group
        } else {
            echo "<p class='text-center text-gray-400'>No order items found.</p>";
        }
        ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
