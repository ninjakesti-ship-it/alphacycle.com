<?php
include 'db_connect.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_name = $_POST['table_name'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

    // ✅ Image upload handling
    $image_name = '';
    if (!empty($_FILES['image']['name'])) {
        $image_name = basename($_FILES['image']['name']);
        $target_path = 'uploads/' . $image_name;

        // Ensure the folder exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($_FILES['image']['tmp_name'], $target_path);
    }

    // ✅ Prepared SQL insert with rating
    $sql = "INSERT INTO `$table_name` (name, price, stock, rating, image)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sdisss', $name, $price, $stock, $rating, $image_name);

    $response = [];

    if ($stmt->execute()) {
        $response = ['success' => true, 'message' => '✅ Product added successfully!'];
    } else {
        $response = ['success' => false, 'message' => '❌ Error: ' . $stmt->error];
    }

    $stmt->close();
    $conn->close();

    // ✅ Return JSON response to your JS fetch()
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product - Alpha Cycle Admin</title>
    <style>
        /* Dark Theme Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212; /* Very dark background */
            color: #e0e0e0; /* Light gray text */
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 40px;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e1e1e; /* Form container background */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #b0b0b0;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            background-color: #2c2c2c; /* Input dark background */
            border: 1px solid #444; /* Darker border */
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            color: #e0e0e0;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #61dafb; /* Light blue focus border */
            box-shadow: 0 0 5px rgba(97, 218, 251, 0.5);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input[type="file"] {
            padding: 8px;
            border: 1px solid #444;
            border-radius: 8px;
            background-color: #2c2c2c;
            color: #e0e0e0;
        }

        .submit-btn {
            background-color: #2ecc71; /* Green color for submit */
            color: #121212;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .submit-btn:hover {
            background-color: #27ad60; /* Darker green on hover */
            transform: translateY(-2px);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #61dafb; /* Light blue link */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #21a1f1;
            text-decoration: underline;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            display:none;
        }

        .message.success {
            background-color: #1a472a; /* Dark green for success */
            color: #d4edda;
            border: 1px solid #155724;
        }

        .message.error {
            background-color: #581618; /* Dark red for error */
            color: #f8d7da;
            border: 1px solid #721c24;
        }
    </style>
</head>
<body>
    <h1>Add New Product</h1>
    <div class="container">
    <div id="response-message" class="message"></div>
    <form id="add-product-form" action="insert_product.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="table_name">Select Category (Table):</label>
            <select id="table_name" name="table_name" required>
                <option value="">-- Select a category --</option>
                <?php foreach ($tables as $value => $label): ?>
                    <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="price">Price (₹):</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity:</label>
            <input type="number" id="stock" name="stock" min="0" required>
        </div>

      

        <!-- ⭐ Review Rating Row -->
        <div class="form-group">
            <label>Rating:</label>
            <div class="rating">
                <label><input type="radio" name="rating" value="5" required> ★★★★★</label><br>
                <label><input type="radio" name="rating" value="4"> ★★★★</label><br>
                <label><input type="radio" name="rating" value="3"> ★★★</label><br>
                <label><input type="radio" name="rating" value="2"> ★★</label><br>
                <label><input type="radio" name="rating" value="1"> ★</label>
            </div>
        </div>
        <!-- End Rating -->

        <div class="form-group">
            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/gif, image/webp">
        </div>

        <button type="submit" class="submit-btn">Add Product</button>
    </form>
</div>

    <a href="manage_stocks.php" class="back-link">← Back to Admin Dashboard</a>

    <script>
        const form = document.getElementById('add-product-form');
        const message = document.getElementById('response-message');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(form);
            message.style.display = 'none';
            fetch(form.action, { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    message.textContent = data.message || 'Done';
                    message.className = 'message ' + (data.success ? 'success' : 'error');
                    message.style.display = 'block';
                    if (data.success) form.reset();
                })
                .catch(() => {
                    message.textContent = 'An unexpected error occurred. Please try again.';
                    message.className = 'message error';
                    message.style.display = 'block';
                });
        });
    </script>
</body>
</html>
