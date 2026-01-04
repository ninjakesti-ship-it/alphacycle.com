<?php
session_start();
include 'db_connect.php';

// Check for order_id in the URL
if (!isset($_GET['order_id'])) {
    // Redirect to a user orders page or home page if no order ID is provided
    header("Location: user_orders.php");
    exit();
}

$order_id = $_GET['order_id'];

// --- Fetch order details from the database ---
$order_sql = "SELECT * FROM orders WHERE order_id = ?";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
$stmt->close();

if (!$order) {
    // Handle case where order is not found
    echo "<p style='text-align: center; font-family: Inter, sans-serif; color: #e0e0e0; background: #1a1c22; padding: 2rem; border-radius: 8px; margin: 2rem;'>Order not found.</p>";
    exit();
}

// --- Fetch order items from the order_items table using the correct column names ---
$items_sql = "SELECT product_name, quantity, price, subtotal FROM order_items WHERE order_id = ?";
$stmt_items = $conn->prepare($items_sql);
$stmt_items->bind_param("s", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt_items->close();

// Seller Info - Hardcoded as per the previous file
$seller_name = "AlphaFuel Cycles Pvt. Ltd.";
$seller_address = "123 Cycle Street, Pune, Maharashtra, India - 411001";
$seller_gst = "27ABCDE1234F2Z5";

$grand_total = (float)$order['total_amount'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= htmlspecialchars($order_id) ?></title>
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
            align-items: flex-start;
            padding: 2rem;
        }
        
        .invoice-container {
            max-width: 900px;
            width: 100%;
            background-color: #1a1c22;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            padding: 3rem;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #00ffaa; /* Green accent */
        }

        .invoice-header .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #00ffff; /* Cyan accent */
        }

        .invoice-header .title {
            font-size: 2rem;
            font-weight: 600;
            color: #00ffaa; /* Green accent */
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .info-block {
            background-color: #262930;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #3a3d45;
        }

        .info-block h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #e0e0e0;
            margin-bottom: 1rem;
        }
        
        .info-line {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed #3a3d45;
        }
        .info-line:last-child { border-bottom: none; }
        
        .info-line span:first-child { color: #a0a0a0; }
        .info-line span:last-child { font-weight: 500; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3rem;
        }
        .items-table th, .items-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #3a3d45;
        }
        .items-table thead th {
            background-color: #262930;
            color: #e0e0e0;
            font-weight: 600;
        }
        .items-table tbody tr:hover { background-color: #30333b; }
        
        /* Updated styles for the summary block */
        .invoice-summary {
            background-color: #262930;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #3a3d45;
            margin-top: 1rem;
            width: 100%; /* Make it full width */
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }
        
        .grand-total-row {
            background-color: #00ffff; /* Cyan accent for the grand total */
            color: #0d0e12;
            padding: 1rem;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 700;
            margin-top: 1rem;
        }

        .actions-section {
            padding-top: 2rem;
            text-align: center;
            clear: both;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-print {
            background-color: #00ffaa; /* Green accent */
            color: #0d0e12;
        }
        .btn-print:hover { background-color: #00c98d; transform: translateY(-2px); }
        
        .btn-track {
            background-color: #00ffff; /* Cyan accent */
            color: #0d0e12;
        }
        .btn-track:hover { background-color: #00c0c0; transform: translateY(-2px); }

        /* New style for the "Continue Shopping" button */
        .btn-continue {
            background-color: #8a2be2; /* Blue-violet color */
            color: #fff;
        }
        .btn-continue:hover {
            background-color: #7b27c3;
            transform: translateY(-2px);
        }

        .footer-note {
            text-align: center;
            color: #a0a0a0;
            font-size: 0.9rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            body { padding: 1rem; }
            .invoice-container { padding: 2rem 1.5rem; }
            .invoice-header { flex-direction: column; text-align: center; }
            .invoice-header .title { margin-top: 0.5rem; }
            .invoice-summary { float: none; max-width: 100%; margin-top: 2rem; }
            .grand-total-row { font-size: 1rem; }
            .actions-section {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
        
        @media print {
            body {
                background-color: #fff;
                color: #000;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
                background-color: #fff;
                padding: 0;
            }
            .info-block, .items-table, .invoice-summary {
                background-color: #f3f4f6;
                color: #000;
                border: 1px solid #ccc;
            }
            .invoice-header, .invoice-header .title {
                color: #000 !important;
                border-bottom-color: #000 !important;
            }
            .info-line span:first-child { color: #555; }
            .info-line span:last-child { color: #000; }
            .grand-total-row { background-color: #c0c0c0 !important; color: #000 !important; }
            .actions-section, .footer-note { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">AlphaFuel Cycles</div>
            <div class="title">INVOICE</div>
        </div>

        <div class="details-grid">
            <div class="info-block">
                <h3>Customer Details</h3>
                <div class="info-line"><span>Name</span><span><?= htmlspecialchars($order['customer_name']) ?></span></div>
                <div class="info-line"><span>Email</span><span><?= htmlspecialchars($order['customer_email']) ?></span></div>
                <div class="info-line"><span>Phone</span><span><?= htmlspecialchars($order['customer_phone']) ?></span></div>
                <div class="info-line"><span>Address</span><span><?= htmlspecialchars($order['customer_address']) ?></span></div>
            </div>
            <div class="info-block">
                <h3>Order Details</h3>
                <div class="info-line"><span>Invoice ID</span><span><?= htmlspecialchars($order['order_id']) ?></span></div>
                <div class="info-line"><span>Order Date</span><span><?= htmlspecialchars(date('d M Y', strtotime($order['order_date']))) ?></span></div>
                <div class="info-line"><span>Status</span><span><?= htmlspecialchars(ucfirst($order['status'])) ?></span></div>
            </div>
        </div>
        
        <h3 class="text-xl font-semibold mb-4 text-white">Order Items</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td>₹<?= number_format((float)$item['price'], 2) ?></td>
                    <td><?= (int)$item['quantity'] ?></td>
                    <td>₹<?= number_format((float)$item['subtotal'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="invoice-summary">
            <?php
            // Calculate subtotal and GST based on the total amount from the orders table.
            $subtotal_before_gst = (float)$order['total_amount'] / 1.18;
            $gst_amount = (float)$order['total_amount'] - $subtotal_before_gst;
            ?>
            <div class="total-row"><span>Subtotal</span><span>₹<?= number_format($subtotal_before_gst, 2) ?></span></div>
            <div class="total-row"><span>GST (18%)</span><span>₹<?= number_format($gst_amount, 2) ?></span></div>
            <div class="grand-total-row"><span>Grand Total</span><span>₹<?= number_format($grand_total, 2) ?></span></div>
        </div>
        
        <div class="actions-section">
            <a href="index.php" class="btn btn-continue">🛒 Continue Shopping</a>
            <button onclick="window.print()" class="btn btn-print">🖨️ Print Invoice</button>
            <a href="track_order.php?order_id=<?= htmlspecialchars($order_id) ?>" class="btn btn-track">🚚 Track Order</a>
        </div>
        
        <div class="footer-note">
            This is a system-generated invoice and does not require a physical signature.<br>
            For any queries, please contact us at support@alphafuelcycles.com
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
