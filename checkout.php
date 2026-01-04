<?php
session_start();
include 'db_connect.php';

// Check if the cart is empty, redirect if it is
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: index.php');
    exit;
}

// --- Pre-fill User Information Logic ---
$customer_name = '';
$customer_email = '';
$customer_phone = '';
$customer_address = '';

// Check if a user is logged in via the session
if (isset($_SESSION['user']['email'])) {
    $customer_email = $_SESSION['user']['email'];

    // Query the database to get the user's full details based on their email
    // We assume a 'users' table exists with columns: name, email, phone, address
    $user_query = "SELECT name, phone, address FROM users WHERE email = ?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param('s', $customer_email);
    $stmt->execute();
    $user_result = $stmt->get_result();

    if ($user_data = $user_result->fetch_assoc()) {
        $customer_name = htmlspecialchars($user_data['name']);
        $customer_phone = htmlspecialchars($user_data['phone']);
        $customer_address = htmlspecialchars($user_data['address']);
    }
    $stmt->close();
}
// --- End of User Information Logic ---

$allowed_tables = ["mountain","gravel","road","ch","kids","ebikes","accessories","clothing",'top_products'];

$cart_items = [];
$total_amount = 0.0;

foreach ($_SESSION['cart'] as $item) {
    $product_id = (int)($item['product_id'] ?? 0);
    $table = (string)($item['table'] ?? '');
    $quantity = (int)($item['quantity'] ?? 0);

    if ($product_id <= 0 || $quantity <= 0 || !in_array($table, $allowed_tables, true)) {
        continue;
    }

    $query = "SELECT id, name, price, stock FROM `$table` WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();
    $stmt->close();

    if ($product) {
        $product['quantity'] = $quantity;
        $product['table'] = $table;
        $product['subtotal'] = (float)$product['price'] * $quantity;
        $product['stock_sufficient'] = ((int)$product['stock'] >= $quantity);
        $total_amount += $product['subtotal'];
        $cart_items[] = $product;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AlphaCycles</title>
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
            align-items: center;
            padding: 2rem;
        }

        .checkout-container {
            max-width: 1200px;
            width: 100%;
            background-color: #1a1c22;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
            padding: 2rem;
        }

        @media (max-width: 1024px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }

        .checkout-header {
            grid-column: 1 / -1;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .checkout-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #00ffff; /* Cyan accent for title */
            letter-spacing: -1px;
        }

        .customer-info, .order-summary {
            background-color: #262930;
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid #3a3d45;
            transition: all 0.3s ease;
        }

        .customer-info h2, .order-summary h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #e0e0e0;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #00ffaa; /* Green accent */
            padding-bottom: 0.5rem;
        }

        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #a0a0a0;
            font-size: 0.9rem;
        }
        .form-group input, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            background-color: #1a1c22;
            border: 1px solid #3a3d45;
            border-radius: 4px;
            color: #ffffff;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #00ffff; /* Cyan accent on focus */
            box-shadow: 0 0 0 3px rgba(0, 255, 255, 0.2); /* Cyan shadow on focus */
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #3a3d45;
            transition: background-color 0.3s ease;
        }
        .order-item:last-child { border-bottom: none; }
        .order-item:hover { background-color: #30333b; }
        .stock-warning {
            background-color: #ef4444;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }
        
        .total-amount-row {
            border-top: 2px solid #00ffaa; /* Green accent */
            padding-top: 1.5rem;
            margin-top: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .place-order-btn {
            background-color: #00ffaa; /* Green accent */
            color: #0d0e12;
            padding: 1rem 2rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .place-order-btn:hover { background-color: #00c98d; transform: translateY(-2px); } /* Darker green hover */
        .place-order-btn:disabled { background-color: #4a5568; color: #a0a0a0; cursor: not-allowed; transform: none; }
        .loading { display: none; text-align: center; padding: 1.5rem; color: #00ffaa; } /* Green accent */
        .success-message, .error-message {
            display: none;
            border-radius: 8px;
            text-align:center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            grid-column: 1 / -1;
        }
        .success-message { background-color: #10b981; color: #000; }
        .error-message { background-color: #ef4444; color: #fff; }

        @media (max-width: 768px) {
            .checkout-container { padding: 1.5rem; }
            .customer-info, .order-summary { padding: 1.5rem; }
            .checkout-header h1 { font-size: 2rem; }
            .customer-info h2, .order-summary h2 { font-size: 1.25rem; }
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
.order-summary {
    background: #0d0d0d;
    border: 1px solid #0ff;
    border-radius: 16px;
    padding: 24px;
    margin: 20px auto;
    max-width: 700px;
    color: #fff;
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
}

.order-title {
    font-size: 1.8rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 24px;
    color: #0ff;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-bottom: 20px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #1a1a1a;
    padding: 16px;
    border-radius: 12px;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.15);
}

.item-info {
    max-width: 70%;
}

.item-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 6px;
}

.item-price {
    font-size: 0.9rem;
    color: #aaa;
    margin-bottom: 10px;
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    background: #0ff;
    color: #000;
    font-size: 1.2rem;
    font-weight: bold;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    transition: 0.2s;
}
.qty-btn:hover {
    background: #00cccc;
}

.qty-display {
    font-size: 1rem;
    min-width: 24px;
    text-align: center;
    font-weight: bold;
    color: #fff;
}

.item-subtotal {
    font-size: 1rem;
    font-weight: bold;
    color: #0ff;
}

.stock-warning {
    margin-top: 6px;
    font-size: 0.85rem;
    color: #ff5555;
    font-weight: bold;
}

.total-amount-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-top: 1px solid rgba(0, 255, 255, 0.3);
    margin-top: 16px;
}

.total-label {
    font-size: 1.2rem;
    font-weight: bold;
}

.total-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #0ff;
}

.place-order-btn {
    display: block;
    width: 100%;
    background: #0ff;
    color: #000;
    font-size: 1.1rem;
    font-weight: bold;
    padding: 14px;
    border: none;
    border-radius: 10px;
    margin-top: 20px;
    cursor: pointer;
    transition: 0.3s;
}
.place-order-btn:hover {
    background: #00cccc;
}

.loading {
    display: none;
    text-align: center;
    margin-top: 16px;
    color: #aaa;
}

.spinner {
    border: 3px solid #222;
    border-top: 3px solid #0ff;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    margin: 10px auto;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    100% {
        transform: rotate(360deg);
    }
}
.customer-info {
    background: #0d0d0d;
    border: 1px solid #0ff;
    border-radius: 16px;
    padding: 32px;
    margin: 30px auto;
    max-width: 1200px;   /* Increased width */
    width: 90%;          /* Makes it responsive */
    color: #fff;
    box-shadow: 0 0 20px rgba(0, 255, 255, 0.25);
}

.customer-info .section-title {
    font-size: 2rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 28px;
    color: #0ff;
}

.customer-info .form-group {
    margin-bottom: 24px;
    display: flex;
    flex-direction: column;
}

.customer-info label {
    font-weight: bold;
    margin-bottom: 8px;
    color: #ccc;
}

.customer-info .required {
    color: #ff5555;
    font-weight: bold;
}

.customer-info input,
.customer-info textarea {
    background: #1a1a1a;
    border: 1px solid rgba(0, 255, 255, 0.5);
    border-radius: 10px;
    padding: 14px;
    font-size: 1rem;
    color: #fff;
    transition: 0.25s;
    width: 100%;
}

.customer-info input:focus,
.customer-info textarea:focus {
    border-color: #0ff;
    box-shadow: 0 0 10px rgba(0, 255, 255, 0.7);
    outline: none;
}

.customer-info textarea {
    resize: none;
}

    </style>
</head>
<body>
    <a href="cart2.php" class="back-button">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
</a>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>Complete Your Order</h1>
        </div>

        <div id="success-message" class="success-message">
            <h2 class="text-xl font-bold mb-2">Order Placed Successfully!</h2>
            <p id="order-details"></p>
        </div>
        <div id="error-message" class="error-message">
            <h2 class="text-xl font-bold mb-2">Order Failed</h2>
            <p id="error-details"></p>
        </div>

<div class="customer-info">
    <h2 class="section-title">👤 Customer Information</h2>

    <form id="checkout-form">
        <div class="form-group">
            <label for="customer-name">Full Name <span class="required">*</span></label>
            <input type="text" id="customer-name" name="name" required 
                   value="<?= htmlspecialchars($customer_name) ?>">
        </div>

        <div class="form-group">
            <label for="customer-email">Email Address <span class="required">*</span></label>
            <input type="email" id="customer-email" name="email" required 
                   value="<?= htmlspecialchars($customer_email) ?>">
        </div>

        <div class="form-group">
            <label for="customer-phone">Phone Number</label>
            <input type="tel" id="customer-phone" name="phone" 
                   value="<?= htmlspecialchars($customer_phone) ?>">
        </div>

        <div class="form-group">
            <label for="customer-address">Delivery Address <span class="required">*</span></label>
            <textarea id="customer-address" name="address" rows="4" required><?= htmlspecialchars($customer_address) ?></textarea>
        </div>
    </form>
</div>


<div class="order-summary">
    <h2 class="order-title">🛒 Order Summary</h2>

    <div class="order-items">
        <?php foreach ($cart_items as $item): ?>
            <div class="order-item">
                <div class="item-info">
                    <h3 class="item-name"><?= htmlspecialchars($item['name']) ?></h3>
                    <p class="item-price">₹<?= number_format((float)$item['price'], 2) ?></p>
                    
                    <div class="quantity-control">
                       
                        <span class="qty-display">Quantity:-<?= (int)$item['quantity'] ?></span>
                   
                    </div>

                    <?php if (!$item['stock_sufficient']): ?>
                        <div class="stock-warning">⚠️ Only <?= (int)$item['stock'] ?> left in stock!</div>
                    <?php endif; ?>
                </div>

                <div class="item-subtotal">
                    <p>₹<?= number_format((float)$item['subtotal'], 2) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="total-amount-row">
        <div class="total-label">Total Amount</div>
        <div class="total-value">₹<?= number_format($total_amount, 2) ?></div>
    </div>

    <button type="button" id="place-order-btn" class="place-order-btn"
        <?= !empty(array_filter($cart_items, fn($i) => !$i['stock_sufficient'])) ? 'disabled' : '' ?>>
        ✅ Confirm & Place Order
    </button>

    <div id="loading" class="loading">
        <p>Processing your order...</p>
        <div class="spinner"></div>
    </div>
</div>


    </div>

    <script>
        document.getElementById('place-order-btn').addEventListener('click', function() {
            const form = document.getElementById('checkout-form');
            const fd = new FormData(form);
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const orderData = {
                customer: {
                    name: fd.get('name'),
                    email: fd.get('email'),
                    phone: fd.get('phone'),
                    address: fd.get('address')
                },
                items: <?= json_encode(array_map(function($i) {
                    return ['product_id' => (int)$i['id'], 'quantity' => (int)$i['quantity'], 'table' => $i['table']];
                }, $cart_items)) ?>
            };

            document.getElementById('loading').style.display = 'block';
            this.disabled = true;

            fetch('process_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(orderData)
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                if (data.success) {
                    document.getElementById('success-message').style.display = 'block';
                    document.getElementById('order-details').innerHTML =
                        `Order ID: <strong>${data.order_id}</strong><br>
                        Total Amount: <strong>₹${Number(data.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</strong><br>
                        <small>Stock has been updated automatically.</small>`;
                    // Hide the main grid after a successful order
                    const mainContent = document.querySelector('.checkout-container');
                    if (mainContent) mainContent.style.display = 'none';
                    
                    setTimeout(() => { window.location.href = 'order_success.php?order_id=' + encodeURIComponent(data.order_id); }, 3000);
                } else {
                    document.getElementById('error-message').style.display = 'block';
                    document.getElementById('error-details').textContent = data.message || 'Order failed.';
                    document.getElementById('place-order-btn').disabled = false;
                }
            })
            .catch(() => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error-message').style.display = 'block';
                document.getElementById('error-details').textContent = 'Network error. Please try again.';
                document.getElementById('place-order-btn').disabled = false;
            });
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>
