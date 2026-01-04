<?php
session_start();
include 'db_connect.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $table = $_POST['table'];
    $quantity = (int)$_POST['quantity'];
    
    // Get product details and check stock
    $stmt = $conn->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Check if enough stock is available
        if ($product['stock'] >= $quantity && $product['stock'] > 0) {
            // Check if item already exists in cart
            $found = false;
            foreach ($_SESSION['cart'] as &$cart_item) {
                if ($cart_item['id'] == $product_id && $cart_item['table'] == $table) {
                    $new_quantity = $cart_item['quantity'] + $quantity;
                    if ($new_quantity <= $product['stock']) {
                        $cart_item['quantity'] = $new_quantity;
                        $found = true;
                        $message = "Updated quantity in cart!";
                    } else {
                        $error = "Cannot add more items. Only {$product['stock']} available.";
                    }
                    break;
                }
            }
            
            if (!$found && !isset($error)) {
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'table' => $table,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $quantity,
                    'image' => $product['image'] ?? ''
                ];
                $message = "Product added to cart!";
            }
        } else {
            $error = "Sorry, insufficient stock available.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlphaFuel Cycles - Premium Bicycles</title>
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
            color: #1e293b;
            line-height: 1.6;
        }

        .header {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .header::before {
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

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: -0.025em;
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .cart-link {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .cart-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.3);
        }

        .cart-count {
            background: #dc2626;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            font-size: 12px;
            margin-left: 8px;
            min-width: 20px;
            text-align: center;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .category {
            margin-bottom: 60px;
        }

        .category-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }

        .category-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border-radius: 2px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 12px;
            margin-bottom: 20px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
        }

        .no-image {
            width: 100%;
            height: 200px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 1rem;
            margin-bottom: 20px;
            border: 2px solid #e2e8f0;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .product-price {
            font-size: 1.5rem;
            color: #2563eb;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .stock-info {
            margin-bottom: 20px;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .stock-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .in-stock {
            background: #dcfce7;
            color: #166534;
        }

        .low-stock {
            background: #fef3c7;
            color: #92400e;
        }

        .out-of-stock {
            background: #fee2e2;
            color: #991b1b;
        }

        .add-to-cart-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quantity-selector label {
            font-weight: 600;
            color: #374151;
        }

        .quantity-input {
            width: 70px;
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: border-color 0.2s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #2563eb;
        }

        .add-to-cart-btn {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .add-to-cart-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .out-of-stock-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
        }

        .out-of-stock-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }

            .container {
                padding: 20px 15px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }

            .product-card {
                padding: 20px;
            }

            .cart-link {
                padding: 12px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .header {
                padding: 30px 15px;
            }

            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>🚴 AlphaFuel Cycles</h1>
            <p>Premium Bicycles for Every Adventure</p>
        </div>
    </div>

    <a href="cart.php" class="cart-link">
        🛒 Cart
        <span class="cart-count"><?= count($_SESSION['cart']) ?></span>
    </a>

    <div class="container">
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php
        $tables = [
            "mountain" => "Mountain Bikes",
            "ebikes" => "ebikes Bikes",
            "road" => "Road Bikes",
            "ch" => "City & Hybrid",
            "ebikes" => "ebikes Bikes",
            "ebikes" => "Electric Bikes",
            "mountain" => "mountain",
            "ebikes" => "ebikes"
        ];

        foreach ($tables as $table => $label) {
            $result = $conn->query("SELECT * FROM `$table` ORDER BY name");
            
            if ($result && $result->num_rows > 0) {
                echo "<div class='category'>";
                echo "<h2 class='category-title'>$label</h2>";
                echo "<div class='products-grid'>";
                
                while ($row = $result->fetch_assoc()) {
                    $stockClass = '';
                    $stockText = '';
                    $isOutOfStock = $row['stock'] == 0;
                    
                    if ($row['stock'] == 0) {
                        $stockClass = 'out-of-stock';
                        $stockText = 'Out of Stock';
                    } elseif ($row['stock'] <= 5) {
                        $stockClass = 'low-stock';
                        $stockText = "Only {$row['stock']} left";
                    } else {
                        $stockClass = 'in-stock';
                        $stockText = "In Stock ({$row['stock']} available)";
                    }
                    
                    $image = '';
                    if (!empty($row['image'])) {
                        $image = "uploads/" . htmlspecialchars($row['image']);
                    }
                    ?>
                    <div class="product-card">
                        <?php if ($isOutOfStock): ?>
                            <div class="out-of-stock-overlay">
                                <div class="out-of-stock-message">Out of Stock</div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($image && file_exists($image)): ?>
                            <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="product-image">
                        <?php else: ?>
                            <div class="no-image">No Image Available</div>
                        <?php endif; ?>
                        
                        <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
                        <div class="product-price">₹<?= number_format($row['price']) ?></div>
                        
                        <div class="stock-info">
                            <span class="stock-badge <?= $stockClass ?>"><?= $stockText ?></span>
                        </div>
                        
                        <?php if (!$isOutOfStock): ?>
                            <form method="POST" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="table" value="<?= $table ?>">
                                
                                <div class="quantity-selector">
                                    <label for="qty-<?= $row['id'] ?>-<?= $table ?>">Quantity:</label>
                                    <input type="number" 
                                           id="qty-<?= $row['id'] ?>-<?= $table ?>" 
                                           name="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="<?= $row['stock'] ?>" 
                                           class="quantity-input" 
                                           required>
                                </div>
                                
                                <button type="submit" name="add_to_cart" class="add-to-cart-btn">
                                    Add to Cart
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="add-to-cart-btn" disabled>Out of Stock</button>
                        <?php endif; ?>
                    </div>
                    <?php
                }
                
                echo "</div></div>";
            }
        }
        ?>
    </div>

    <script>
        // Add smooth scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.product-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Validate quantity before form submission
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const quantityInput = this.querySelector('.quantity-input');
                const max = parseInt(quantityInput.getAttribute('max'));
                const value = parseInt(quantityInput.value);
                
                if (value > max) {
                    e.preventDefault();
                    alert(`Maximum available quantity is ${max}`);
                    quantityInput.value = max;
                }
            });
        });
    </script>
</body>
</html>
