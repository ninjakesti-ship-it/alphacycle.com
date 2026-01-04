<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AlphaFuel</title>
    <style>
        /* Dark Theme Styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212; /* Very dark background */
            color: #e0e0e0;
            display: flex;
            height: 100vh;
            overflow: hidden; /* Prevents scrollbars on the body */
        }

        .sidebar {
            width: 250px;
            background-color: #1e1e1e; /* Dark sidebar background */
            color: white;
            transition: width 0.3s ease, background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 2px 0 10px rgba(0,0,0,0.5);
            border-right: 1px solid #333;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar h2 {
            text-align: center;
            padding: 20px 10px;
            font-size: 22px;
            margin: 0;
            border-bottom: 1px solid #333;
            white-space: nowrap; /* Prevents title from wrapping when sidebar collapses */
            overflow: hidden;
            text-overflow: ellipsis;
            transition: font-size 0.3s ease, padding 0.3s ease;
        }

        .sidebar.collapsed h2 {
            font-size: 0;
            padding: 0;
            border-bottom: none;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 1px solid #2c2c2c;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li:hover {
            background-color: #333333; /* Slightly lighter dark on hover */
        }
        
        .sidebar.collapsed ul li {
            text-align: center;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #e0e0e0;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.3s ease;
        }
        
        .sidebar.collapsed ul li a span {
            display: none;
        }

        .toggle-btn {
            background: #2ecc71; /* Green color for the toggle button */
            color: white;
            text-align: center;
            cursor: pointer;
            padding: 10px;
            font-size: 24px;
            transition: background-color 0.3s ease;
        }

        .toggle-btn:hover {
            background-color: #27ad60;
        }
        
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #121212; /* Match body background */
            display: flex;
            flex-direction: column;
            overflow: auto; /* Allows content to be scrollable */
        }

        iframe {
            width: 100%;
            flex-grow: 1;
            border: none;
            background-color: #1e1e1e; /* iframe background to match sidebar */
            border-radius: 8px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.3);
        }

        .logout-btn {
            background-color: #e74c3c; /* Red color for logout */
            padding: 15px 20px;
            text-align: center;
            text-decoration: none;
            color: white;
            display: block;
            border-top: 1px solid #333;
            transition: background-color 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: absolute;
                z-index: 10;
                height: 100%;
            }
            .main-content {
                margin-left: 60px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div>
        <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
        <h2>Alpha Cycles Admin</h2>
        <ul>
            <li><a href="add_product.php" target="contentFrame">➕ Add Product</a></li>
            <li><a href="admin_order_status.php" target="contentFrame">📦 View Orders</a></li>
            <li><a href="view_items.php" target="contentFrame">📄 View Order Items</a></li>
            <li><a href="manage_stocks.php" target="contentFrame">📊 Manage Stocks</a></li>
            <li><a href="view_users.php" target="contentFrame">👥 View Users</a></li>
            <li><a href="delivery_insights.php" target="contentFrame">🚚 Delivery Insights</a></li>
        </ul>
    </div>
    <a href="login.php" class="logout-btn">🔓 Logout</a>
</div>

<div class="main-content">
    <iframe name="contentFrame" src="welcome_admin.html"></iframe>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }
</script>

</body>
</html>
