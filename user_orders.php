<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start a session and set the timezone
session_start();
date_default_timezone_set('Asia/Kolkata');

// Include the database connection file
include 'db_connect.php';

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: userlogin.php");
    exit();
}

// Get the user's email from the session
$email = $_SESSION['user']['email'];

// SQL query to select all necessary order details for the logged-in user
// The 'orders' table schema from your SQL file is used here.
// Columns selected: order_id, status, total_amount, order_date, order_time
$sql = "SELECT order_id, status, total_amount, order_date, order_time FROM orders WHERE customer_email = ? ORDER BY id DESC";

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the email parameter and execute the statement
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all rows into an array for easier processing
$orders = $result->fetch_all(MYSQLI_ASSOC);
$total_orders = count($orders);

// Initialize variables to calculate statistics
$total_spent = 0;
$status_counts = ['shipped' => 0, 'delivered' => 0, 'cancelled' => 0, 'pending' => 0];

// Loop through the orders to calculate total spent and status counts
foreach ($orders as $row) {
    $total_spent += $row['total_amount'];
    $status = strtolower($row['status']);
    if (isset($status_counts[$status])) {
        $status_counts[$status]++;
    }
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - AlphaFuel Cycles</title>
    <!-- Use a modern, techy font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a2e 50%, #16213e 100%);
            color: #f1f1f1;
            font-family: 'Rajdhani', sans-serif;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(0, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0, 255, 127, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(0, 191, 255, 0.1) 0%, transparent 50%);
            animation: backgroundShift 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes backgroundShift {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .header h2 {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: #00ffff;
            text-shadow: 
                0 0 10px #00ffff,
                0 0 20px #00ffff,
                0 0 40px #00ffff;
            margin-bottom: 1rem;
            letter-spacing: 2px;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                text-shadow: 
                    0 0 10px #00ffff,
                    0 0 20px #00ffff,
                    0 0 40px #00ffff;
            }
            to {
                text-shadow: 
                    0 0 5px #00ffff,
                    0 0 10px #00ffff,
                    0 0 20px #00ffff;
            }
        }

        .header p {
            font-size: 1.2rem;
            color: #a0a0a0;
            font-weight: 300;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.8) 0%, rgba(34, 34, 34, 0.8) 100%);
            border: 1px solid rgba(0, 255, 255, 0.3);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .stat-card:hover::before {
            left: 100%;
        }

        .stat-card h3 {
            font-family: 'Orbitron', monospace;
            font-size: 2rem;
            color: #00ffff;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .orders-section {
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.9) 0%, rgba(34, 34, 34, 0.9) 100%);
            border: 1px solid rgba(0, 255, 255, 0.2);
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(15px);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .section-title {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            color: #00ffff;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title::before {
            content: '⚡';
            font-size: 1.2rem;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid rgba(0, 255, 255, 0.2);
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            background: linear-gradient(135deg, rgba(17, 17, 17, 0.8) 0%, rgba(34, 34, 34, 0.8) 100%);
        }

        .order-table th {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.1) 0%, rgba(0, 191, 255, 0.1) 100%);
            color: #00ffff;
            padding: 20px;
            text-align: left;
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(0, 255, 255, 0.3);
            position: relative;
        }

        .order-table td {
            padding: 18px 20px;
            color: #f1f1f1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 1rem;
            font-weight: 400;
        }

        .order-table tr {
            transition: all 0.3s ease;
            position: relative;
        }

        .order-table tbody tr:hover {
            background: linear-gradient(135deg, rgba(0, 255, 255, 0.05) 0%, rgba(0, 191, 255, 0.05) 100%);
            transform: translateX(5px);
            box-shadow: -5px 0 15px rgba(0, 255, 255, 0.2);
        }

        .order-id {
            font-family: 'Orbitron', monospace;
            font-weight: 600;
            color: #00bfff;
            background: rgba(0, 191, 255, 0.1);
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid rgba(0, 191, 255, 0.3);
            display: inline-block;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .status-shipped {
            background: linear-gradient(135deg, rgba(0, 255, 127, 0.2) 0%, rgba(0, 255, 127, 0.1) 100%);
            color: #00ff7f;
            border: 1px solid rgba(0, 255, 127, 0.4);
            box-shadow: 0 0 10px rgba(0, 255, 127, 0.3);
        }

        .status-cancelled {
            background: linear-gradient(135deg, rgba(255, 76, 76, 0.2) 0%, rgba(255, 76, 76, 0.1) 100%);
            color: #ff4c4c;
            border: 1px solid rgba(255, 76, 76, 0.4);
            box-shadow: 0 0 10px rgba(255, 76, 76, 0.3);
        }

        .status-delivered {
            background: linear-gradient(135deg, rgba(0, 191, 255, 0.2) 0%, rgba(0, 191, 255, 0.1) 100%);
            color: #00bfff;
            border: 1px solid rgba(0, 191, 255, 0.4);
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
        }

        .status-pending {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.2) 0%, rgba(255, 193, 7, 0.1) 100%);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.4);
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.3);
        }

        .amount {
            font-family: 'Orbitron', monospace;
            font-weight: 600;
            color: #00ffff;
            font-size: 1.1rem;
        }

        .time {
            color: #a0a0a0;
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-family: 'Orbitron', monospace;
            font-size: 1.5rem;
            color: #00ffff;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #00ffff 0%, #00bfff 100%);
            color: #000;
            padding: 12px 24px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 255, 255, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 255, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header h2 {
                font-size: 2rem;
            }

            .orders-section {
                padding: 1rem;
            }

            .order-table th,
            .order-table td {
                padding: 12px 8px;
                font-size: 0.85rem;
            }

            .order-table th {
                font-size: 0.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .order-table th:nth-child(4),
            .order-table td:nth-child(4) {
                display: none;
            }

            .header h2 {
                font-size: 1.5rem;
            }
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(0, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #00ffff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    .back-button {
    position: absolute;
    top: 2rem;
    left: 2rem;
    color: #00ffff; /* Cyan accent color */
    background-color: #1a1c22;
    border: 1px solid #3a3d45;
    border-radius: 50%;
    padding: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 10;
}

.back-button:hover {
    background-color: #262930;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.5); /* Glowing effect on hover */
    transform: scale(1.1);
}

.back-button svg {
    height: 1.5rem;
    width: 1.5rem;
}
    </style>
</head>
<body>
        <a href="index.php" class="back-button">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
</a>
    <div class="container">
        <div class="header">
            <h2>ORDER HISTORY</h2>
            <p>Track your purchases and order status</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3><?= $total_orders ?></h3>
                <p>Total Orders</p>
            </div>
            <div class="stat-card">
                <h3>₹<?= number_format($total_spent, 0) ?></h3>
                <p>Total Spent</p>
            </div>
            <div class="stat-card">
                <h3><?= $status_counts['delivered'] ?></h3>
                <p>Delivered</p>
            </div>
            <div class="stat-card">
                <h3><?= $status_counts['shipped'] ?></h3>
                <p>Shipped</p>
            </div>
        </div>

        <div class="orders-section">
            <h3 class="section-title">Recent Orders</h3>
            
            <?php if ($total_orders > 0): ?>
                <div class="table-container">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $row): 
                                $status = strtolower($row['status']);
                                $status_class = 'status-' . $status;

                                // Use 'order_time' from the database which includes both date and time
                                $order_datetime_str = $row['order_time'];
                                // Create a DateTime object and format it for display
                                $order_datetime = new DateTime($order_datetime_str, new DateTimeZone('Asia/Kolkata'));
                            ?>
                                <tr>
                                    <td><span class="order-id"><?= htmlspecialchars($row['order_id']) ?></span></td>
                                    <td><span class="status-badge <?= $status_class ?>"><?= htmlspecialchars(ucfirst($row['status'])) ?></span></td>
                                    <td><span class="amount">₹<?= number_format($row['total_amount'], 2) ?></span></td>
                                    <td><span class="time"><?= $order_datetime->format('d M Y, h:i A') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="icon">🛒</div>
                    <h3>NO ORDERS YET</h3>
                    <p>You haven't placed any orders yet. Start shopping to see your order history here.</p>
                    <a href="index.php" class="cta-button">Start Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        // Add entrance animations
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.order-table tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, index * 100);
            });

            // Add stats animation
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });

        // Add click effects
        document.querySelectorAll('.order-table tbody tr').forEach(row => {
            row.addEventListener('click', function() {
                this.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });
    </script>
</body>
</html>
