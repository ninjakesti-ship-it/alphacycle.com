<?php
include 'db_connect.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cycle Store - Shop</title>
    <style>
        body { font-family: Arial; background-color: #f9f9f9; padding: 20px; }
        h1 { text-align: center; }
        .product {
            background: white;
            padding: 20px;
            margin: 10px auto;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            width: 400px;
        }
        form { margin-top: 10px; }
        input[type=number] { width: 50px; }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<h1>Shop Cycles</h1>

<?php
$result = $conn->query("SELECT * FROM products WHERE stock > 0");
while ($row = $result->fetch_assoc()) {
    echo "<div class='product'>
            <h3>{$row['name']}</h3>
            <p>Price: ₹{$row['price']}</p>
            <form method='POST' action='add_to_cart.php'>
                <input type='hidden' name='product_id' value='{$row['id']}'>
                Quantity: <input type='number' name='quantity' value='1' min='1' max='{$row['stock']}'>
                <button type='submit'>Add to Cart</button>
            </form>
          </div>";
}
?>
</body>
</html>
