<?php
// This PHP script handles the admin dashboard for managing product stocks.
// It displays products from various categories and provides options to update stock or delete products.

// Include the database connection file.
// NOTE: This file is assumed to contain the database connection logic.
include 'db_connect.php';

// Define the product categories and their corresponding database table names.
$tables = [
    "mountain"      => "Mountain Bikes",
    "gravel"        => "Gravel Bikes",
    "road"          => "Road Bikes",
    "ch"            => "City & Hybrid",
    "kids"          => "Kids Bikes",
    "ebikes"        => "Electric Bikes",
    "accessories"   => "Accessories",
    "clothing"      => "Clothing"
];

/**
 * Checks if a given table exists in the database.
 * This is a safety check to prevent errors if a category table is missing.
 * @param mysqli $conn The database connection object.
 * @param string $table The name of the table to check.
 * @return bool True if the table exists, false otherwise.
 */
function table_exists(mysqli $conn, string $table): bool {
    // Use a prepared statement to safely check for table existence.
    $stmt = $conn->prepare("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Alpha Cycle</title>
    <style>
        /* Dark Theme Styles with Cyan and Green */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212; /* Very dark background */
            color: #e0e0e0;
            padding: 30px;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        h2 {
            font-size: 24px;
            margin-top: 40px;
            margin-bottom: 20px;
            text-align: center;
            color: #00bcd4; /* Vibrant cyan for headings */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1e1e1e; /* Darker background for table */
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #2c2c2c; /* Subtle border */
        }

        th {
            background-color: #282c34; /* Dark header color */
            font-weight: bold;
            color: #ffffff;
        }

        tr:hover {
            background-color: #333333; /* Slightly lighter dark on hover */
            transition: background-color 0.3s ease;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: contain; /* Ensures the whole image is visible */
            border-radius: 5px;
            background-color: #2c2c2c; /* Background for contained images */
        }

        form {
            display: inline-flex;
            gap: 10px;
            align-items: center;
            margin-top: 5px;
        }

        input[type="number"] {
            padding: 8px;
            border: 1px solid #444;
            border-radius: 5px;
            width: 90px;
            background-color: #2c2c2c;
            color: #e0e0e0;
            transition: border-color 0.3s ease;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #00bcd4;
        }

        .btn {
            background-color: #2ecc71; /* Vibrant green for buttons */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #27ad60;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: #e74c3c; /* Red for delete button */
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }

        .notice {
            max-width: 960px;
            margin: 12px auto;
            padding: 10px 14px;
            border-radius: 6px;
            background-color: #1a472a; /* Dark green for success messages */
            border: 1px solid #155724;
            color: #d4edda;
            text-align: center;
        }
        
        /* Styles for the floating notification */
        #notification-box {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #2ecc71;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            z-index: 1000;
            display: none;
            transition: all 0.5s ease;
        }
        
        #notification-box.error {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
<h1>Alpha Cycle - Admin Dashboard</h1>

<?php if (isset($_GET['msg'])): ?>
    <!-- This notice is for server-side redirects, kept for compatibility -->
    <div class="notice"><?= htmlspecialchars($_GET['msg']) ?></div>
<?php endif; ?>

<!-- Notification box for AJAX responses -->
<div id="notification-box"></div>

<?php
foreach ($tables as $table => $label) {
    echo "<h2>" . htmlspecialchars($label) . "</h2>";

    if (!table_exists($conn, $table)) {
        echo "<div class='notice'>Table <strong>" . htmlspecialchars($table) . "</strong> does not exist. You can create it from the SQL file provided or change allowed categories.</div>";
        continue;
    }

    echo "<table>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Image</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>";

    $sql = "SELECT id, name, price, stock, image FROM `$table` ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $id    = (int)$row['id'];
            $name  = htmlspecialchars($row['name'] ?? '');
            $price = number_format((float)($row['price'] ?? 0), 2);
            $stock = (int)($row['stock'] ?? 0);
            $image = $row['image'] ?? '';
            ?>
            <tr>
                <td><?= $id ?></td>
                <td><?= $name ?></td>
                <td>₹<?= $price ?></td>
                <td><?= $stock ?></td>
                <td>
                    <?php if (!empty($image)): ?>
                        <img src="uploads/<?= htmlspecialchars($image) ?>" alt="<?= $name ?>" class="product-image">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td>
                    <form class="update-stock-form" action="update_stock.php" method="post">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
                        <input type="number" name="stock" value="<?= $stock ?>" min="0" required>
                        <input type="submit" value="Update Stock" class="btn">
                    </form>
                    <form action="delete_product.php" method="post" onsubmit="return confirm('Are you sure you want to delete this product?')">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </form>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='6'>No products found in " . htmlspecialchars($label) . "</td></tr>";
    }
    echo "</tbody></table>";
}
?>

<!-- Moved the Add Product button to the top left -->
<div style="position: fixed; top: 20px; left: 20px; z-index: 1000;">
    <a href="add_product.php" 
        style="background-color: #2ecc71; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2); transition: background-color 0.3s ease;">
        <span style="color: white;">➕</span> Add Product
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Select all forms that are used for updating stock.
    const updateForms = document.querySelectorAll('.update-stock-form');
    const notificationBox = document.getElementById('notification-box');

    // Add a submit event listener to each form.
    updateForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission (page reload).
            
            const fd = new FormData(this);
            const submitBtn = this.querySelector('input[type="submit"]');
            
            // Clear previous notification and disable the button.
            notificationBox.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.value = 'Updating...';

            fetch(this.action, {
                method: 'POST',
                body: fd
            })
            .then(response => {
                // Check if the response is okay (status code 200-299)
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Display a success or error notification.
                notificationBox.textContent = data.message || 'Update successful!';
                notificationBox.className = data.success ? '' : 'error';
                notificationBox.style.display = 'block';

                // Re-enable the button and restore text.
                submitBtn.disabled = false;
                submitBtn.value = 'Update Stock';
                
                // Hide the notification after a few seconds.
                setTimeout(() => {
                    notificationBox.style.display = 'none';
                }, 3000);
            })
            .catch(error => {
                // Handle fetch errors (network, server, or invalid JSON).
                console.error('Fetch Error:', error);
                notificationBox.textContent = 'An error occurred. Please check the server response and try again.';
                notificationBox.className = 'error';
                notificationBox.style.display = 'block';
                
                // Re-enable the button.
                submitBtn.disabled = false;
                submitBtn.value = 'Update Stock';
                
                // Hide the notification after a few seconds.
                setTimeout(() => {
                    notificationBox.style.display = 'none';
                }, 3000);
            });
        });
    });
});
</script>

</body>
</html>
<?php $conn->close(); ?>
