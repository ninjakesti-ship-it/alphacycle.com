<?php
session_start();
include 'db_connect.php';

// Cart count
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Bikes - AlphaFuel</title>
    
    <!-- External Resources -->
    <link rel="stylesheet" href="j.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="ebike-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js" type="module"></script>
    
    <style>
        /* ===== GLOBAL STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Roboto", sans-serif;
            background-color: #000;
            color: #fff;
            line-height: 1.6;
        }

        /* ===== HEADER STYLES ===== */
        .header-nav {
            transition: all 0.3s ease;
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background-color: #00ffff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .cart-button {
            position: relative;
            transition: all 0.3s ease;
        }

        .cart-button:hover {
            transform: scale(1.1);
        }

        .badge {
            animation: pulse 2s infinite;
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            background: linear-gradient(135deg, #000 0%, #0a0a0a 50%, #000 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(0, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            background: linear-gradient(135deg, #00ffff 0%, #00ffaa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: glow-text 3s ease-in-out infinite alternate;
        }

        /* ===== PRODUCTS SECTION ===== */
        #products {
            padding: 4rem 2rem;
            background: linear-gradient(180deg, #0a0a0a 0%, #000 50%, #0a0a0a 100%);
            position: relative;
        }

        #products::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 70% 30%, rgba(0, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        #products h2 {
            text-align: center;
            color: #00ffff;
            font-size: 2.5rem;
            font-weight: bold;
            text-shadow: 
                0 0 10px #00ffff,
                0 0 20px #00ffff,
                0 0 30px #00ffff;
            margin-bottom: 3rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-family: 'Orbitron', sans-serif;
            position: relative;
            z-index: 2;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2rem;
            max-width: 1600px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        /* ===== CARD STYLES ===== */
        .card {
            position: relative;
            background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%);
            color: #ccc;
            border-radius: 16px;
            overflow: hidden;
            padding: 28px;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(0, 255, 255, 0.1);
            border: 1px solid rgba(0, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
            min-height: 420px;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #00ffff, #00ffaa, #00cfff, #00ffff);
            background-size: 300% auto;
            animation: glow-line 3s linear infinite;
        }

        .card::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, #00ffff, #00ffaa, #00cfff, #00ffff);
            background-size: 300% auto;
            animation: glow-line 3s linear infinite reverse;
        }

        .card .glow-left,
        .card .glow-right {
            position: absolute;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(180deg, #00ffff, #00ffaa, #00cfff, #00ffff);
            background-size: auto 300%;
            animation: glow-line-vertical 3s linear infinite;
        }

        .card .glow-left {
            left: 0;
        }

        .card .glow-right {
            right: 0;
            animation-delay: 1.5s;
        }

        .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.4),
                0 0 40px rgba(0, 255, 255, 0.3);
            border-color: rgba(0, 255, 255, 0.6);
        }

        /* ===== PRODUCT IMAGE ===== */
        .card img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background: linear-gradient(145deg, #000 0%, #111 100%);
            border: 2px solid #333;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.4s ease;
            padding: 10px;
        }

        .card:hover img {
            transform: scale(1.05);
            border-color: #00ffff;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
        }

        .card h3 {
            font-size: 1.3rem;
            margin-bottom: 12px;
            color: #fff;
            font-weight: 600;
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 1px;
        }

        /* ===== PRICE & STOCK ===== */
        .price {
            font-weight: bold;
            color: #00ffaa;
            font-size: 1.4rem;
            margin-bottom: 8px;
            font-family: 'Orbitron', sans-serif;
            text-shadow: 0 0 10px rgba(0, 255, 170, 0.5);
        }

        .stock {
            color: #bbb;
            margin-bottom: 20px;
            font-size: 0.95rem;
            padding: 4px 8px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            display: inline-block;
        }

        /* ===== BUTTON ACTIONS ===== */
        .actions {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: auto;
            padding-top: 16px;
        }

        .actions button {
            flex: 1;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 1px;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            min-width: 140px;
        }

        .actions button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .actions button:hover::before {
            left: 100%;
        }

        .actions button.buy {
            background: linear-gradient(135deg, #00c98d 0%, #00ffaa 100%);
            border: 1px solid rgba(0, 255, 170, 0.5);
            box-shadow: 0 4px 15px rgba(0, 201, 141, 0.3);
        }

        .actions button.buy:hover {
            background: linear-gradient(135deg, #00ffaa 0%, #00c98d 100%);
            box-shadow: 0 6px 20px rgba(0, 255, 170, 0.5);
            transform: translateY(-2px);
        }

        .actions button.add {
            background: linear-gradient(135deg, #007bff 0%, #00cfff 100%);
            border: 1px solid rgba(0, 207, 255, 0.5);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }

        .actions button.add:hover {
            background: linear-gradient(135deg, #00cfff 0%, #007bff 100%);
            box-shadow: 0 6px 20px rgba(0, 207, 255, 0.5);
            transform: translateY(-2px);
        }

        /* ===== GLOW BUTTON ===== */
        .glow-button {
            position: relative;
            display: inline-block;
            padding: 16px 40px;
            color: #00ffff;
            background: transparent;
            border: 2px solid #00ffff;
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            overflow: hidden;
            z-index: 1;
            transition: all 0.4s ease;
            cursor: pointer;
            border-radius: 8px;
        }

        .glow-button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 255, 255, 0.3) 10%, transparent 60%);
            animation: pulse-glow 3s linear infinite;
            z-index: -1;
            opacity: 0.7;
        }

        .glow-button::after {
            content: '';
            position: absolute;
            inset: 0;
            border: 2px solid #00ffff;
            border-radius: 8px;
            animation: border-wave 2.5s linear infinite;
            z-index: -2;
        }

        .glow-button:hover {
            color: #000;
            background-color: #00ffff;
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.6);
            transform: translateY(-2px);
        }

        /* ===== FOOTER STYLES ===== */
        .site-footer {
            background: linear-gradient(180deg, #0a0a0a 0%, #000 100%);
            padding: 3rem 2rem 1rem;
            border-top: 1px solid rgba(0, 255, 255, 0.2);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .footer-column h4 {
            color: #00ffff;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        .policy-links {
            list-style: none;
        }

        .policy-links li {
            margin-bottom: 0.5rem;
        }

        .policy-links a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .policy-links a:hover {
            color: #00ffff;
        }

        .social-icons {
            display: flex;
            gap: 1rem;
        }

        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(0, 255, 255, 0.1);
            border: 1px solid rgba(0, 255, 255, 0.3);
            border-radius: 50%;
            text-align: center;
            line-height: 38px;
            color: #00ffff;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: rgba(0, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .newsletter-form {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .newsletter-form input {
            flex: 1;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(0, 255, 255, 0.3);
            border-radius: 6px;
            color: #fff;
        }

        .newsletter-form input::placeholder {
            color: #aaa;
        }

        .newsletter-form button {
            padding: 0.75rem 1rem;
            background: #00ffff;
            border: none;
            border-radius: 6px;
            color: #000;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            background: #00cfff;
            transform: translateY(-1px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #aaa;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* ===== NO PRODUCTS MESSAGE ===== */
        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: #aaa;
            font-size: 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes glow-line {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 0%; }
        }

        @keyframes glow-line-vertical {
            0% { background-position: 0% 0%; }
            100% { background-position: 0% 100%; }
        }

        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        @keyframes border-wave {
            0% { box-shadow: 0 0 15px rgba(0, 255, 255, 0.5); }
            50% { box-shadow: 0 0 25px rgba(0, 255, 255, 0.8); }
            100% { box-shadow: 0 0 15px rgba(0, 255, 255, 0.5); }
        }

        @keyframes glow-text {
            0% { text-shadow: 0 0 20px rgba(0, 255, 255, 0.5); }
            100% { text-shadow: 0 0 30px rgba(0, 255, 255, 0.8); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .product-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 0 1rem;
            }

            #products {
                padding: 3rem 1rem;
            }

            #products h2 {
                font-size: 2rem;
            }

            .actions {
                flex-direction: row;
                gap: 12px;
            }

            .actions button {
                flex: 1;
                min-width: auto;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .nav-link {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 20px;
            }

            .card img {
                height: 180px;
            }

            .price {
                font-size: 1.2rem;
            }

            .glow-button {
                padding: 12px 24px;
                font-size: 12px;
            }

            .hero-content h1 {
                font-size: 2rem;
            }
        }

        /* ===== LOADING ANIMATION ===== */
        .loading {
            opacity: 0;
            animation: fadeIn 0.6s ease-in-out forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        /* ===== NOTIFICATION STYLES ===== */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: #000;
            font-weight: 600;
            z-index: 1000;
            transform: translateX(100%);
            animation: slideIn 0.3s ease-out forwards;
        }

        .notification.success {
            background: linear-gradient(135deg, #00ffaa, #00c98d);
        }

        .notification.error {
            background: linear-gradient(135deg, #ff4444, #cc0000);
            color: #fff;
        }

        @keyframes slideIn {
            to { transform: translateX(0); }
        }
    </style>
</head>

<body>
    <!-- ===== HEADER ===== -->
    <header class="absolute top-0 left-0 right-0 z-50">
        <nav class="container mx-auto px-6 py-6 flex justify-between items-center text-white header-nav">
            <!-- Logo -->
            <div class="flex items-center text-white">
                <svg fill="none" height="36" viewBox="0 0 24 24" width="36" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
                <span class="ml-3 font-orbitron text-2xl font-bold tracking-widest text-white">ALPHA</span>
                <span class="ml-1 font-orbitron text-2xl font-light tracking-widest text-white">CYCLES</span>
            </div>
            
            <!-- Navigation Links -->
            <div class="flex items-center space-x-12 font-orbitron text-lg text-white">
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="index.php">HOME</a>
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="categories.html">BIKES</a>
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="about.html">ABOUT US</a>
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="contact.html">CONTACT US</a>
                
                <!-- Cart -->
                <a href="cart.php" class="cart-button text-2xl relative hover:text-cyan-400 transition-colors duration-300">
                    🛒
                    <?php if ($cart_count > 0): ?>
                        <span class="badge absolute top-[-10px] right-[-10px] bg-cyan-400 text-black text-xs px-2 py-0.5 rounded-full">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </nav>
    </header>

    <!-- ===== HERO SECTION ===== -->
    <section class="hero-section bg-black min-h-screen flex items-center justify-center px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center text-white py-16">
            <!-- Left Content -->
            <div class="hero-content loading">
                <h1 class="hero-title text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight">
                    Unbeatable Mountain Bike<br> 
                    Deals Now Delivered<br> 
                    Your Way
                </h1>
                <p class="mt-6 text-lg text-gray-300 max-w-md">
                    Choose in-store pickup, fast home delivery, or expert assembly right at your doorstep.
                </p>
                <button 
                    onclick="smoothScrollTo('products')"
                    class="glow-button mt-6">
                    Explore Products
                </button>
            </div>
            
            <!-- Right Content - Animation -->
            <div class="flex justify-center loading">
                <dotlottie-wc 
                    src="https://lottie.host/b6381256-eb8e-4f40-928e-3303f10f87a4/eUrBXjNvdV.lottie" 
                    style="width: 500px; height: 500px" 
                    speed="1" 
                    autoplay 
                    loop>
                </dotlottie-wc>
            </div>
        </div>
    </section>

    <!-- ===== PRODUCTS SECTION ===== -->
    <section id="products">
        <h2>Our E-Bike Collection</h2>
        <div class="product-grid">
            <?php
            $sql = "SELECT * FROM ebikes ORDER BY name ASC";
            $result = $conn->query($sql);
            
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_id = htmlspecialchars($row['id']);
                    $product_name = htmlspecialchars($row['name']);
                    $product_price = htmlspecialchars($row['price']);
                    $product_stock = htmlspecialchars($row['stock']);
                    $product_image = htmlspecialchars($row['image']);
                    ?>
                    
                    <div class="card loading">
                        <span class="glow-left"></span>
                        <span class="glow-right"></span>
                        
                        <img src="uploads/<?= $product_image ?>" 
                             alt="<?= $product_name ?>" 
                             loading="lazy"
                             onerror="this.src='/placeholder.svg?height=220&width=300&text=<?= urlencode($product_name) ?>'">
                        
                        <h3><?= $product_name ?></h3>
                        <p class="price">₹<?= number_format($product_price) ?></p>
                        <p class="stock">Stock: <?= $product_stock ?> units</p>
                        
                        <div class="actions">
                            <form method="POST" action="add_to_cart.php" style="display:inline;" onsubmit="return handleAddToCart(event, <?= $product_id ?>)">
                                <input type="hidden" name="product_id" value="<?= $product_id ?>">
                                <input type="hidden" name="table" value="ebikes">
                                <button type="submit" class="add">Add to Cart</button>
                            </form>
                            <button class="buy" onclick="buyNow(<?= $product_id ?>)">Buy Now</button>
                        </div>
                    </div>
                    
                    <?php
                }
            } else {
                echo '<div class="no-products">
                        <p>🚴‍♂️ No E-Bikes available at the moment.</p>
                        <p>Check back soon for amazing deals!</p>
                      </div>';
            }
            ?>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-column">
                <h4>POLICIES</h4>
                <ul class="policy-links">
                    <li><a href="refund.html">Refund Policy</a></li>
                    <li><a href="privacy.html">Privacy Policy</a></li>
                    <li><a href="terms.html">Terms of Service</a></li>
                    <li><a href="shipping.html">Shipping Policy</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4>FOLLOW US</h4>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="Instagram">◉</a>
                    <a href="#" aria-label="YouTube">▶</a>
                </div>
            </div>
            
            <div class="footer-column">
                <h4>Join our community</h4>
                <p>Sign Up for monthly Give Aways</p>
                <form class="newsletter-form" onsubmit="handleNewsletter(event)">
                    <input type="email" placeholder="Your email" required>
                    <button type="submit" aria-label="Subscribe">➤</button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>© 2025, alphafuel.com. Powered by You.</p>
            <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg" alt="PayPal" height="20">
        </div>
    </footer>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        // ===== GSAP INITIALIZATION =====
        gsap.registerPlugin(ScrollTrigger);

        // Initialize animations when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeAnimations();
            initializeScrollEffects();
            initializeLazyLoading();
        });

        // ===== ANIMATIONS =====
        function initializeAnimations() {
            // Animate loading elements
            gsap.from('.loading', {
                duration: 0.8,
                y: 30,
                opacity: 0,
                stagger: 0.2,
                ease: 'power2.out'
            });

            // Animate product cards on scroll
            gsap.from('.card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                stagger: 0.15,
                scrollTrigger: {
                    trigger: '#products',
                    start: 'top 80%',
                    end: 'bottom 20%',
                    toggleActions: 'play none none reverse'
                }
            });

            // Animate section title
            gsap.from('#products h2', {
                duration: 1,
                scale: 0.8,
                opacity: 0,
                scrollTrigger: {
                    trigger: '#products h2',
                    start: 'top 85%',
                    toggleActions: 'play none none reverse'
                }
            });
        }

        // ===== SCROLL EFFECTS =====
        function initializeScrollEffects() {
            // Smooth scroll function
            window.smoothScrollTo = function(elementId) {
                const element = document.getElementById(elementId);
                if (element) {
                    element.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            };

            // Header background on scroll
            window.addEventListener('scroll', function() {
                const header = document.querySelector('header');
                if (window.scrollY > 100) {
                    header.style.background = 'rgba(0, 0, 0, 0.9)';
                    header.style.backdropFilter = 'blur(10px)';
                } else {
                    header.style.background = 'transparent';
                    header.style.backdropFilter = 'none';
                }
            });
        }

        // ===== LAZY LOADING =====
        function initializeLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        // ===== PRODUCT FUNCTIONS =====
        function buyNow(productId) {
            showNotification('Redirecting to checkout...', 'success');
            
            // Add to cart first, then redirect
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('table', 'ebikes');

            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                setTimeout(() => {
                    window.location.href = 'checkout.php';
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error processing request. Please try again.', 'error');
            });
        }

        function handleAddToCart(event, productId) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            
            fetch('add_to_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                showNotification('Product added to cart successfully!', 'success');
                updateCartCount();
                
                // Add visual feedback
                const button = event.target.querySelector('button[type="submit"]');
                const originalText = button.textContent;
                button.textContent = 'Added!';
                button.style.background = 'linear-gradient(135deg, #00ffaa, #00c98d)';
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '';
                }, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding product to cart. Please try again.', 'error');
            });
            
            return false;
        }

        // ===== NEWSLETTER FORM =====
        function handleNewsletter(event) {
            event.preventDefault();
            const email = event.target.querySelector('input[type="email"]').value;
            
            showNotification('Thank you for subscribing to our newsletter!', 'success');
            event.target.reset();
            
            // Here you would typically send the email to your backend
            console.log('Newsletter signup:', email);
        }

        // ===== CART FUNCTIONS =====
        function updateCartCount() {
            fetch('get_cart_count.php')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.badge');
                const cartButton = document.querySelector('.cart-button');
                
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                        gsap.fromTo(badge, 
                            { scale: 1.5 }, 
                            { scale: 1, duration: 0.3, ease: 'back.out(1.7)' }
                        );
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'badge absolute top-[-10px] right-[-10px] bg-cyan-400 text-black text-xs px-2 py-0.5 rounded-full';
                        newBadge.textContent = data.count;
                        cartButton.appendChild(newBadge);
                        
                        gsap.fromTo(newBadge, 
                            { scale: 0, opacity: 0 }, 
                            { scale: 1, opacity: 1, duration: 0.4, ease: 'back.out(1.7)' }
                        );
                    }
                } else if (badge) {
                    gsap.to(badge, {
                        scale: 0,
                        opacity: 0,
                        duration: 0.3,
                        onComplete: () => badge.remove()
                    });
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
        }

        // ===== UTILITY FUNCTIONS =====
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                gsap.to(notification, {
                    x: '100%',
                    opacity: 0,
                    duration: 0.3,
                    onComplete: () => {
                        if (document.body.contains(notification)) {
                            document.body.removeChild(notification);
                        }
                    }
                });
            }, 4000);
        }

        // ===== ERROR HANDLING =====
        window.addEventListener('error', function(e) {
            console.error('JavaScript error:', e.error);
        });

        // ===== PERFORMANCE OPTIMIZATION =====
        // Preload critical resources
        window.addEventListener('load', function() {
            // Preload cart count endpoint
            fetch('get_cart_count.php').catch(() => {});
        });

        // ===== KEYBOARD NAVIGATION =====
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                // Close any open modals or notifications
                document.querySelectorAll('.notification').forEach(notification => {
                    notification.remove();
                });
            }
        });

        // ===== ACCESSIBILITY IMPROVEMENTS =====
        // Focus management for buttons
        document.querySelectorAll('button, a').forEach(element => {
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid #00ffff';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = 'none';
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
