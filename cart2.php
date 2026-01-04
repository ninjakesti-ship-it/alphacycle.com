<?php
session_start();
include 'db_connect.php';

// Ensure database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Allowed tables
$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing"];

// =======================
// Handle AJAX requests
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'update_quantity') {
        $product_id = (int)$_POST['product_id'];
        $table = $_POST['table'];
        $quantity = max(1, (int)$_POST['quantity']); // ensure at least 1

        if (!in_array($table, $allowed_tables)) {
            echo json_encode(['success' => false, 'message' => 'Invalid table']);
            exit;
        }

        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] == $product_id && $item['table'] === $table) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }

    if ($_POST['action'] === 'remove_item') {
        $product_id = (int)$_POST['product_id'];
        $table = $_POST['table'];

        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) use ($product_id, $table) {
                return !($item['product_id'] == $product_id && $item['table'] === $table);
            });
        }

        echo json_encode(['success' => true]);
        exit;
    }

    // New action to clear the entire cart
    if ($_POST['action'] === 'clear_cart') {
        unset($_SESSION['cart']);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// =======================
// Display cart page
// =======================
$cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_count = 0;
$cart_products = [];
$total_amount = 0;

if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        if (
            empty($item['product_id']) ||
            empty($item['table']) ||
            !in_array($item['table'], $allowed_tables) ||
            (int)$item['quantity'] <= 0
        ) {
            continue;
        }

        $product_id = (int)$item['product_id'];
        $table = $item['table'];
        $quantity = (int)$item['quantity'];

        $sql = "SELECT id, name, price, stock, image 
                FROM `$table` 
                WHERE id = $product_id 
                LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);

            if ($quantity > $product['stock']) {
                $quantity = $product['stock'];
            }

            $product['quantity'] = $quantity;
            $product['table'] = $table;
            $product['subtotal'] = $product['price'] * $quantity;
            $total_amount += $product['subtotal'];
            $cart_products[] = $product;
            $cart_count += $quantity;
        }
    }
}

$shipping_cost = $total_amount > 0 ? 299.00 : 0;
$tax_rate = 0.18;
$tax_amount = $total_amount * $tax_rate;
$final_total = $total_amount + $shipping_cost + $tax_amount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - AlphaCycles</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="cart-styles.css">
    <style>
        .clear-cart-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .clear-cart-btn {
            background-color: #ef4444; /* Tailwind's red-500 */
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 500; /* font-medium */
            transition: background-color 0.3s;
        }
        .clear-cart-btn:hover {
            background-color: #dc2626; /* Tailwind's red-600 */
        }
        .cart-actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
}

.cart-actions button {
    padding: 5px 15px;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #00ffff; /* cyan */
    color: #0d0e12;      /* dark background text */
    box-shadow: 0 4px 10px rgba(0, 255, 255, 0.3);
}

.cart-actions button:hover {
    background: #00c0c0; /* darker cyan on hover */
    transform: translateY(-3px);
    box-shadow: 0 6px 14px rgba(0, 255, 255, 0.5);
}

    </style>
</head>
<body class="bg-black text-white min-h-screen">

<header class="absolute top-0 left-0 right-0 z-50">
    <nav class="container mx-auto px-6 py-6 flex justify-between items-center text-white">
        <div class="flex items-center text-white">
            <span class="ml-3 font-orbitron text-2xl font-bold tracking-widest">ALPHA</span>
            <span class="ml-1 font-orbitron text-2xl font-light tracking-widest">CYCLES</span>
        </div>
        <div class="flex items-center space-x-12 font-orbitron text-lg">
            <a class="hover:text-cyan-400 tracking-widest" href="index.php">HOME</a>
            <a class="hover:text-cyan-400 tracking-widest" href="index.php#categories-section">BIKES</a>
            <a class="hover:text-cyan-400 tracking-widest" href="about.html">ABOUT US</a>
            <a class="hover:text-cyan-400 tracking-widest" href="contact.html">CONTACT US</a>
            <div class="text-2xl relative text-cyan-400">
                🛒
                <?php if ($cart_count > 0): ?>
                    <span class="absolute top-[-10px] right-[-10px] bg-cyan-400 text-black text-xs px-2 py-0.5 rounded-full">
                        <?= $cart_count ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<main class="pt-32 pb-16">
    <div class="container mx-auto px-6 max-w-7xl">

        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-orbitron font-bold tracking-wide mb-4">
                Your Shopping Cart
            </h1>
            <p class="text-gray-300 text-lg">Review your items and proceed to checkout</p>
        </div>

        <?php if (!empty($cart_products)): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <div class="lg:col-span-2" id="cart-items-container">
                <?php foreach ($cart_products as $product): ?>
                    <div class="cart-item-content">
                        <img src="uploads/<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="cart-item-image"
                             onerror="this.src='uploads/placeholder.png'">

                        <div class="cart-item-details">
                            <h4><?= htmlspecialchars($product['name']) ?></h4>
                            <div class="cart-item-price">₹<?= number_format($product['price'], 2) ?></div>
                            <div class="text-gray-400 text-sm">Stock: <?= $product['stock'] ?> available</div>
                            <div class="text-cyan-400 text-sm font-semibold">Subtotal: ₹<?= number_format($product['subtotal'], 2) ?></div>
                        </div>

                        <div class="cart-item-controls">
                            <div class="quantity-controls">
                                <button onclick="updateQuantity(<?= $product['id'] ?>, '<?= $product['table'] ?>', <?= $product['quantity'] - 1 ?>)">−</button>
                                <span><?= $product['quantity'] ?></span>
                                <button onclick="updateQuantity(<?= $product['id'] ?>, '<?= $product['table'] ?>', <?= $product['quantity'] + 1 ?>)" <?= $product['quantity'] >= $product['stock'] ? 'disabled' : '' ?>>+</button>
                            </div>
                            <button onclick="removeFromCart(<?= $product['id'] ?>, '<?= $product['table'] ?>')">Remove</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="lg:col-span-1">
                <div class="cart-summary">
                    <h3 class="text-2xl font-orbitron font-bold mb-6">Order Summary</h3>
                    <div class="summary-line"><span>Subtotal:</span> <span>₹<?= number_format($total_amount, 2) ?></span></div>
                    <div class="summary-line"><span>Shipping:</span> <span><?= $shipping_cost > 0 ? '₹' . number_format($shipping_cost, 2) : 'Free' ?></span></div>
                    <div class="summary-line"><span>Tax (18% GST):</span> <span>₹<?= number_format($tax_amount, 2) ?></span></div>
                    <hr class="border-gray-600 my-4">
                    <div class="summary-line total"><span>Total:</span> <span>₹<?= number_format($final_total, 2) ?></span></div>
              <div class="cart-actions">
                  <button class="continue-btn" onclick="continueShopping()">Continue Shopping</button>
    <button class="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
  
</div>

                </div>
            </div>

        </div>
        <div class="clear-cart-container">
            <button class="clear-cart-btn" onclick="clearCart()">Clear Cart</button>
        </div>

        <?php else: ?>
        <div class="empty-cart-state text-center">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-3xl font-orbitron font-bold mb-4">Your cart is empty</h2>
            <p class="text-gray-300 mb-8 text-lg">Looks like you haven't added any items yet.</p>
   <a href="index.php#categories-section" class="cart-actions">
    Explore Products
</a>


        </div>
        <?php endif; ?>
    </div>
</main>

<script>
function updateQuantity(productId, table, quantity) {
    if (quantity <= 0) return;
    fetch('cart2.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'update_quantity',
            product_id: productId,
            table: table,
            quantity: quantity
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

function removeFromCart(productId, table) {
    fetch('cart2.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'remove_item',
            product_id: productId,
            table: table
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) location.reload();
    });
}

// New JavaScript function to clear the cart
function clearCart() {
    if (confirm('Are you sure you want to clear your cart? This action cannot be undone.')) {
        fetch('cart2.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'clear_cart'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('An error occurred while clearing the cart.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

function proceedToCheckout() {
    window.location.href = 'checkout.php';
}

function continueShopping() {
    window.location.href = 'index.php';
}
</script>

</body>
</html>