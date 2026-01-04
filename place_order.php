<?php
session_start();
include 'db_connect.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
$name = $_POST['name'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$order_id = strtoupper(uniqid("ORD"));
$order_date = date("d-m-Y");
$order_time = date("h:i A");

// Seller Info
$seller_name = "AlphaFuel Cycles Pvt. Ltd.";
$seller_address = "123 Cycle Street, Pune, Maharashtra, India - 411001";
$seller_gst = "27ABCDE1234F2Z5";

// Calculate total with GST
foreach ($cart as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $gst = $item_total * 0.18;
    $total += $item_total + $gst;
}

// Save to orders table
$stmt = $conn->prepare("INSERT INTO orders (order_id, customer_name, customer_email, customer_address, customer_phone, total_amount, order_date, order_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$order_time_db = date("H:i:s");
$stmt->bind_param("sssssdss", $order_id, $name, $email, $address, $phone, $total, $order_date, $order_time_db);
$stmt->execute();
$stmt->close();

// Save each item to order_items table
foreach ($cart as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $gst = $item_total * 0.18;
    $total_with_gst = $item_total + $gst;
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, product_price, quantity, gst, total_with_gst) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_item->bind_param("ssiddi", $order_id, $item['name'], $item['price'], $item['quantity'], $gst, $total_with_gst);
    $stmt_item->execute();
    $stmt_item->close();
}

// Clear cart after successful order
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - AlphaFuel Cycles</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
            min-height: 100vh;
            padding: 20px;
            color: #1e293b;
            line-height: 1.6;
        }

        .success-banner {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-align: center;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .success-banner h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .success-banner p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .checkmark {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .invoice-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateX(-50px) translateY(-50px); }
            100% { transform: translateX(50px) translateY(50px); }
        }

        .invoice-header-content {
            position: relative;
            z-index: 1;
        }

        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        .company-logo {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .invoice-title p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .invoice-content {
            padding: 40px;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .info-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            border: 1px solid #e2e8f0;
        }

        .info-card h3 {
            color: #2563eb;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
        }

        .order-id {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .items-section {
            margin-bottom: 40px;
        }

        .section-title {
            color: #2563eb;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.025em;
        }

        .items-table td {
            padding: 16px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.95rem;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody tr:hover {
            background: #f8fafc;
        }

        .product-name {
            font-weight: 600;
            color: #1e293b;
        }

        .price {
            color: #2563eb;
            font-weight: 600;
        }

        .quantity-badge {
            background: #eff6ff;
            color: #2563eb;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            min-width: 40px;
            text-align: center;
        }

        .gst-amount {
            color: #059669;
            font-weight: 600;
        }

        .total-section {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .total-row:last-child {
            border-bottom: none;
            font-size: 1.5rem;
            font-weight: 700;
            margin-top: 12px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }

        .actions-section {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-bottom: 40px;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background: #f8fafc;
            color: #2563eb;
            border: 2px solid #e2e8f0;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .thank-you-section {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            text-align: center;
            padding: 60px 40px;
            border-radius: 16px;
            margin-top: 40px;
        }

        .thank-you-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
            letter-spacing: -0.025em;
        }

        .thank-you-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .footer-note {
            background: #f8fafc;
            padding: 24px;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            border-top: 1px solid #e2e8f0;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .success-banner,
            .actions-section,
            .thank-you-section {
                display: none;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }

            .invoice-header {
                background: #2563eb !important;
                -webkit-print-color-adjust: exact;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .invoice-header {
                padding: 30px 20px;
            }

            .company-info {
                flex-direction: column;
                gap: 20px;
            }

            .invoice-title {
                text-align: left;
            }

            .invoice-content {
                padding: 20px;
            }

            .order-info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .items-table {
                font-size: 0.85rem;
            }

            .items-table th,
            .items-table td {
                padding: 12px 8px;
            }

            .actions-section {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .thank-you-section {
                padding: 40px 20px;
            }

            .thank-you-section h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .items-table th:nth-child(4),
            .items-table td:nth-child(4) {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="success-banner">
        <h1>
            <span class="checkmark">✓</span>
            Order Placed Successfully!
        </h1>
        <p>Your order has been confirmed and will be processed shortly</p>
    </div>

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-header-content">
                <div class="company-info">
                    <div class="company-logo">
                        🚴 AlphaFuel Cycles
                    </div>
                    <div class="invoice-title">
                        <h2>Invoice</h2>
                        <p>Order Confirmation</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-content">
            <div class="order-info-grid">
                <div class="info-card">
                    <h3>📋 Order Information</h3>
                    <div class="info-item">
                        <span class="info-label">Order ID</span>
                        <span class="order-id"><?= $order_id ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Date</span>
                        <span class="info-value"><?= $order_date ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Order Time</span>
                        <span class="info-value"><?= $order_time ?></span>
                    </div>
                </div>

                <div class="info-card">
                    <h3>👤 Customer Details</h3>
                    <div class="info-item">
                        <span class="info-label">Name</span>
                        <span class="info-value"><?= htmlspecialchars($name) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($email) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone</span>
                        <span class="info-value"><?= htmlspecialchars($phone) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address</span>
                        <span class="info-value"><?= htmlspecialchars($address) ?></span>
                    </div>
                </div>
            </div>

            <div class="info-card" style="margin-bottom: 40px;">
                <h3>🏪 Seller Information</h3>
                <div class="info-item">
                    <span class="info-label">Company</span>
                    <span class="info-value"><?= $seller_name ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Address</span>
                    <span class="info-value"><?= $seller_address ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">GST Number</span>
                    <span class="info-value"><?= $seller_gst ?></span>
                </div>
            </div>

            <div class="items-section">
                <h3 class="section-title">🛍️ Order Items</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price (₹)</th>
                            <th>Quantity</th>
                            <th>GST (18%)</th>
                            <th>Total (Incl. GST)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $subtotal = 0;
                        $total_gst = 0;
                        foreach ($cart as $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $gst = $item_total * 0.18;
                            $total_with_gst = $item_total + $gst;
                            $subtotal += $item_total;
                            $total_gst += $gst;
                        ?>
                            <tr>
                                <td class="product-name"><?= htmlspecialchars($item['name']) ?></td>
                                <td class="price">₹<?= number_format($item['price'], 2) ?></td>
                                <td><span class="quantity-badge"><?= $item['quantity'] ?></span></td>
                                <td class="gst-amount">₹<?= number_format($gst, 2) ?></td>
                                <td class="price">₹<?= number_format($total_with_gst, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>₹<?= number_format($subtotal, 2) ?></span>
                </div>
                <div class="total-row">
                    <span>GST (18%)</span>
                    <span>₹<?= number_format($total_gst, 2) ?></span>
                </div>
                <div class="total-row">
                    <span>Grand Total</span>
                    <span>₹<?= number_format($total, 2) ?></span>
                </div>
            </div>

            <div class="actions-section">
                <button onclick="window.print()" class="btn btn-primary">
                    🖨️ Print Invoice
                </button>
                <a href="index.php" class="btn btn-secondary">
                    🛍️ Continue Shopping
                </a>
            </div>

            <div class="footer-note">
                This is a system-generated invoice and does not require a physical signature.<br>
                For any queries, please contact us at support@alphafuelcycles.com
            </div>
        </div>
    </div>

    <div class="thank-you-section">
        <h2>Thank You for Choosing AlphaFuel Cycles!</h2>
        <p>We appreciate your business and look forward to serving you again. Your order will be processed and shipped within 2-3 business days.</p>
    </div>

    <script>
        // Auto-scroll to top on page load
        window.addEventListener('load', function() {
            window.scrollTo(0, 0);
        });

        // Add loading state to print button
        function printInvoice() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '⏳ Preparing...';
            btn.disabled = true;
            
            setTimeout(() => {
                window.print();
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 500);
        }
    </script>
</body>
</html>
