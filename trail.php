<?php
session_start();
include 'db_connect.php';

// Cart count
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Alpha Cycles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="dt.css">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #000;
            color: #fff;
            overflow-x: hidden;
        }
        
        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        
        .text-cyan-glow {
            text-shadow: 
                0 0 6px #00ffff,
                0 0 12px #00ffff,
                0 0 18px #00ffff;
        }
        
        .glow-button {
            position: relative;
            display: inline-block;
            padding: 14px 32px;
            color: #00ffff;
            background: transparent;
            border: 2px solid #00ffff;
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            overflow: hidden;
            z-index: 1;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .glow-button::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, #00ffff66 10%, transparent 60%);
            animation: pulse-glow 2.5s linear infinite;
            z-index: -1;
            opacity: 0.5;
        }
        
        .glow-button:hover {
            color: #000;
            background-color: #00ffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 255, 255, 0.3);
        }
        
        @keyframes pulse-glow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.4;
            }
            50% {
                transform: scale(1.4);
                opacity: 0.8;
            }
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        /* Animation Classes */
        .animate-element {
            transition: all 0.8s ease-out;
        }
        
        .animate-element.duration-1000 {
            transition-duration: 1000ms;
        }
        
        .animate-element.duration-1200 {
            transition-duration: 1200ms;
        }
        
        /* Initial states for different animations */
        .slide-in-left {
            opacity: 0;
            transform: translateX(-80px);
        }
        
        .slide-in-right {
            opacity: 0;
            transform: translateX(80px);
        }
        
        .slide-in-up {
            opacity: 0;
            transform: translateY(80px);
        }
        
        .slide-in-down {
            opacity: 0;
            transform: translateY(-80px);
        }
        
        .scale-in {
            opacity: 0;
            transform: scale(0.75);
        }
        
        .rotate-in {
            opacity: 0;
            transform: rotate(12deg) scale(0.75);
        }
        
        .bounce-in {
            opacity: 0;
            transform: scale(0.5);
        }
        
        .flip-in {
            opacity: 0;
            transform: rotateY(90deg);
        }
        
        .zoom-in {
            opacity: 0;
            transform: scale(0);
        }
        
        .fade-in {
            opacity: 0;
            transform: translateY(40px);
        }
        
        /* Visible states */
        .animate-element.visible {
            opacity: 1;
            transform: translateX(0) translateY(0) scale(1) rotate(0) rotateY(0);
        }
        
        /* Testimonial styles */
        .testimonial-track {
            display: flex;
            transition: transform 0.7s ease-in-out;
        }
        
        .testimonial-card {
            flex: 0 0 25%;
            padding: 0 8px;
        }
        
        .testimonial-inner {
            background: #0f0f0f;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.5s ease;
            height: 100%;
        }
        
        .testimonial-inner:hover {
            transform: scale(1.1) translateY(-12px);
            box-shadow: 0 0 30px rgba(0, 255, 255, 0.3);
            background: rgba(55, 65, 81, 0.5);
        }
        
        /* FAQ Styles */
        .faq-item {
            margin-bottom: 16px;
        }
        
        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: rgba(17, 24, 39, 0.5);
            border: 1px solid #374151;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            background: rgba(31, 41, 55, 0.5);
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
        }
        
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s ease-in-out;
            opacity: 0;
            background: #111827;
            border: 1px solid #374151;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        
        .faq-answer.open {
            max-height: 200px;
            opacity: 1;
            padding: 16px;
        }
        
        .faq-icon {
            transition: all 0.5s ease;
        }
        
        .faq-icon.rotated {
            transform: rotate(180deg) scale(1.1);
        }

        /* Categories Section Styles */
        .glow-heading {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
            color: #00ffff;
            text-shadow: 
                0 0 6px #00ffff,
                0 0 12px #00ffff,
                0 0 18px #00ffff;
            margin-bottom: 3rem;
            letter-spacing: 0.2em;
        }

        .categories-section {
            padding: 4rem 1.5rem;
            background: #000;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        .category-card {
            position: relative;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            text-decoration: none;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: all 0.5s ease;
            border: 2px solid transparent;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 1.5rem;
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(0, 0, 0, 0.3) 0%,
                rgba(0, 0, 0, 0.7) 100%
            );
            transition: all 0.3s ease;
            z-index: 1;
        }

        .category-card:hover::before {
            background: linear-gradient(
                135deg,
                rgba(0, 255, 255, 0.1) 0%,
                rgba(0, 0, 0, 0.5) 100%
            );
        }

        .category-card span {
            position: relative;
            z-index: 2;
            color: #fff;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-align: center;
            transition: all 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .category-card:hover {
            transform: translateY(-8px) scale(1.02);
            border-color: #00ffff;
            box-shadow: 
                0 10px 30px rgba(0, 255, 255, 0.3),
                0 0 20px rgba(0, 255, 255, 0.2);
        }

        .category-card:hover span {
            color: #00ffff;
            text-shadow: 
                0 0 10px #00ffff,
                2px 2px 4px rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .glow-heading {
                font-size: 2.5rem;
            }
            
            .category-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }
            
            .category-card {
                height: 180px;
            }
        }

        @media (max-width: 480px) {
            .glow-heading {
                font-size: 2rem;
            }
            
            .category-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .category-card {
                height: 160px;
            }
        }

        .hero-title {
            background: linear-gradient(135deg, #00ffff 0%, #00ffaa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: glow-text 3s ease-in-out infinite alternate;
        }

        /* Carousel Integration Styles */
        .carousel-section {
            margin: 4rem 0;
            position: relative;
        }

        .carousel-title {
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Ensure carousel works with our animation system */
        .carousel.animate-element {
            opacity: 0;
            transform: scale(0.9);
        }

        .carousel.animate-element.visible {
            opacity: 1;
            transform: scale(1);
        }
        
        .cart-button {
            position: relative;
            transition: all 0.3s ease;
        }

        .cart-button:hover {
            transform: scale(1.1);
        }

    </style>
</head>

<body class="bg-black text-white">
    <div class="min-h-screen flex flex-col">
        
        <!-- Header - Slide Down Animation -->
        <header class="animate-element slide-in-down duration-1000 fixed top-0 left-0 right-0 z-50 bg-black bg-opacity-90 backdrop-blur-sm" data-delay="0">
            <nav class="container mx-auto px-6 py-6 flex justify-between items-center">
                <!-- Logo & Brand -->
                <div class="flex items-center text-white">
                    <svg class="text-white" fill="none" height="36" viewBox="0 0 24 24" width="36" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                    <span class="ml-3 font-orbitron text-2xl font-bold tracking-widest text-cyan-400">ALPHA</span>
                    <span class="ml-1 font-orbitron text-2xl font-light tracking-widest text-white">CYCLES</span>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-10 font-orbitron text-lg text-white">
                    <a class="hover:text-cyan-400 tracking-widest transition-all duration-300 hover:scale-110" href="#home">HOME</a>
                    <a class="hover:text-cyan-400 tracking-widest transition-all duration-300 hover:scale-110" href="#categories-section">BIKES</a>
                 
                    <a class="hover:text-cyan-400 tracking-widest transition-all duration-300 hover:scale-110" href="contact.html">CONTACT US</a>
                    <a class="hover:text-cyan-400 tracking-widest transition-all duration-300 hover:scale-110" href="about.html">ABOUT US</a>
                </div>

                <?php if (isset($_SESSION['user'])): ?>
                <div class="relative ml-4">
                    <button id="userMenuBtn" class="w-10 h-10 rounded-full border-2 border-cyan-400 text-cyan-400 font-bold flex items-center justify-center hover:bg-cyan-800 transition">
                        <?= strtoupper($_SESSION['user']['name'][0]) ?>
                    </button>
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-black border border-cyan-400 text-white rounded-lg shadow-lg z-50">
                        <div class="px-4 py-2 border-b border-cyan-400">
                            <p class="text-cyan-300 text-sm"><?= htmlspecialchars($_SESSION['user']['name']) ?></p>
                            <p class="text-xs text-gray-400"><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
                        </div>
                        <div class="px-4 py-2 hover:bg-cyan-700 border-b border-cyan-400">
                            <a href="user_orders.php" class="text-cyan-300 hover:text-white">My Orders</a>
                        </div>
          <div class="px-4 py-2 hover:bg-cyan-700 border-b border-cyan-400">
                            <a href="cart2.php" class="text-cyan-300 hover:text-white">My Cart</a>
                        </div>
                        <div class="px-4 py-2 hover:bg-cyan-700 rounded-b">
                            <a href="logout.php" class="text-red-400 hover:text-white">Logout</a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="font-orbitron text-sm ml-4">
                    <a href="userlogin.php" class="text-cyan-400 hover:underline transition-all duration-300 hover:scale-110">Login</a>
                </div>
                <?php endif; ?>
                               <a href="cart2.php" class="cart-button text-2xl relative hover:text-cyan-400 transition-colors duration-300">
                    🛒
                    <?php if ($cart_count > 0): ?>
                        <span class="badge absolute top-[-10px] right-[-10px] bg-cyan-400 text-black text-xs px-2 py-0.5 rounded-full">
                            <?= $cart_count ?>
                        </span>
                    <?php endif; ?>
                </a>
            </nav>
        </header>

        <!-- Hero Section - Split Animation -->
        <main class="flex-grow flex items-center pt-24" id="home">
            <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 items-center gap-12">
                <!-- Hero Text - Slide In Left -->
                <div class="animate-element slide-in-left duration-1200" data-delay="300">
                    <div class="text-left">
                        <h1 class="font-orbitron font-bold text-5xl tracking-wider">ALPHA</h1>
                        <h2 class="font-orbitron font-bold text-8xl text-cyan-400 text-cyan-glow tracking-widest -mt-2">CYCLES</h2>

                        <p class="mt-4 text-gray-400 max-w-md">
                            Ride the Future. Own the Road., Built for Every Trail, Made for Every Journey.
                        </p>
                        <a href="#categories-section" class="glow-button mt-8 inline-block">Explore More</a>
                    </div>
                </div>
                
                <!-- Hero Image - Slide In Right -->
                <div class="animate-element slide-in-right duration-1200" data-delay="500">
                    <div class="relative">
                        <img alt="Futuristic black bicycle" class="w-full transform hover:scale-105 transition-transform duration-500" src="cycle.png"/>
                    </div>
                </div>
            </div>
        </main>

        <!-- Stats Footer - Staggered Slide Up -->
        <footer class="container mx-auto px-6 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                <div class="animate-element slide-in-up transform hover:scale-105 transition-transform duration-300" data-delay="200">
                    <p class="text-gray-400 text-sm">Customer Rating</p>
                    <p class="font-orbitron text-2xl font-bold">4.8 <span class="text-lg font-normal">/ 5</span></p>
                </div>
                <div class="animate-element slide-in-up transform hover:scale-105 transition-transform duration-300" data-delay="400">
                    <p class="text-gray-400 text-sm">Delivery Network</p>
                    <p class="font-orbitron text-2xl font-bold">500+ <span class="text-lg font-normal">Cities in India</span></p>
                </div>
                <div class="animate-element slide-in-up transform hover:scale-105 transition-transform duration-300" data-delay="600">
                    <p class="text-gray-400 text-sm">Warranty</p>
                    <p class="font-orbitron text-2xl font-bold">3 Years <span class="text-lg font-normal">on All Bikes</span></p>
                </div>
            </div>
        </footer>

        <!-- 3D Carousel Section - Scale In Animation -->
  <section class="carousel-section animate-element duration-600" data-delay="0">
    <div class="carousel-title animate-element zoom-in duration-600" data-delay="200">
        <h2 class="hero-title text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight mb-4">
            Our Top Products
        </h2>
    </div>

    <div class="carousel animate-element fade-in duration-600" data-delay="300">
        <div class="list">

            <!-- Item 1 -->
            <div class="item">
                <img src="3.jpg" alt="TerraClimb X9 Mountain Bike">
                <div class="introduce animate-element fade-in duration-300" data-delay="50">
                    <div class="title">MOUNTAIN MASTER</div>
                    <div class="topic">TerraClimb X9</div>
                    <div class="des">
                        Conquer rugged terrains with the TerraClimb X9 mountain bike, built with a lightweight aluminum frame and hydraulic disc brakes for superior control.
                    </div>
                    <button class="seeMore">SEE MORE &#8599;</button>
                </div>
                <div class="detail">
                    <div class="title">TerraClimb X9 Series</div>
                    <div class="des">
                        Engineered for off-road adventures. 27.5" wheels, 12-speed SRAM gearing, and advanced suspension make it ideal for serious mountain bikers.
                    </div>
                    <div class="specifications">
                        <div><p>Frame</p><p>Aluminum</p></div>
                        <div><p>Brakes</p><p>Hydraulic Disc</p></div>
                        <div><p>Gears</p><p>12-Speed</p></div>
                        <div><p>Wheel Size</p><p>27.5"</p></div>
                        <div><p>Suspension</p><p>Front Air</p></div>
                    </div>
                    <div class="checkout">
                        <button>ADD TO CART</button>
                        <button>CHECKOUT</button>
                    </div>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="item">
                <img src="2.PNG" alt="VelociX Aero Road Bike">
                <div class="introduce animate-element fade-in duration-300" data-delay="50">
                    <div class="title">ROAD RACER</div>
                    <div class="topic">VelociX Aero</div>
                    <div class="des">
                        Built for speed, the VelociX Aero is a carbon-frame road bike with aerodynamic design and Shimano Ultegra drivetrain for peak performance.
                    </div>
                    <button class="seeMore">SEE MORE &#8599;</button>
                </div>
                <div class="detail">
                    <div class="title">VelociX Aero Carbon</div>
                    <div class="des">
                        Professional-grade road bike offering seamless speed and precision. Ideal for competitive cyclists and long-distance racers.
                    </div>
                    <div class="specifications">
                        <div><p>Frame</p><p>Carbon Fiber</p></div>
                        <div><p>Gearing</p><p>Shimano Ultegra</p></div>
                        <div><p>Weight</p><p>7.8kg</p></div>
                        <div><p>Wheels</p><p>700c Aero</p></div>
                        <div><p>Brakes</p><p>Disc</p></div>
                    </div>
                    <div class="checkout">
                        <button>ADD TO CART</button>
                        <button>CHECKOUT</button>
                    </div>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="item">
                <img src="4.jpg" alt="UrbanFlow E-Bike">
                <div class="introduce animate-element fade-in duration-300" data-delay="50">
                    <div class="title">CITY COMMUTER</div>
                    <div class="topic">UrbanFlow E-Bike</div>
                    <div class="des">
                        Make commuting effortless with UrbanFlow, a lightweight e-bike with pedal assist, built-in lights, and up to 60km of range.
                    </div>
                    <button class="seeMore">SEE MORE &#8599;</button>
                </div>
                <div class="detail">
                    <div class="title">UrbanFlow E-Commute</div>
                    <div class="des">
                        Daily commute redefined with electric assistance and a sleek, compact design. Foldable frame available in select models.
                    </div>
                    <div class="specifications">
                        <div><p>Motor</p><p>250W Hub</p></div>
                        <div><p>Battery</p><p>36V 10Ah</p></div>
                        <div><p>Range</p><p>60km</p></div>
                        <div><p>Charge Time</p><p>4 Hours</p></div>
                        <div><p>Features</p><p>LED Lights, Rack</p></div>
                    </div>
                    <div class="checkout">
                        <button>ADD TO CART</button>
                        <button>CHECKOUT</button>
                    </div>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="item">
                <img src="4.PNG" alt="MiniRider 16 Kids Bike">
                <div class="introduce animate-element fade-in duration-300" data-delay="50">
                    <div class="title">KIDS SERIES</div>
                    <div class="topic">MiniRider 16</div>
                    <div class="des">
                        Safe, colorful, and lightweight — the perfect first bike for kids aged 4–7. Includes training wheels and adjustable seat.
                    </div>
                    <button class="seeMore">SEE MORE &#8599;</button>
                </div>
                <div class="detail">
                    <div class="title">MiniRider Kids 16"</div>
                    <div class="des">
                        Designed for young riders with safety in mind. Puncture-proof tires, vibrant colors, and full chain guard for protection.
                    </div>
                    <div class="specifications">
                        <div><p>Wheel Size</p><p>16"</p></div>
                        <div><p>Age</p><p>4–7</p></div>
                        <div><p>Brakes</p><p>Coaster + Hand</p></div>
                        <div><p>Weight</p><p>6.5kg</p></div>
                        <div><p>Safety</p><p>Training Wheels</p></div>
                    </div>
                    <div class="checkout">
                        <button>ADD TO CART</button>
                        <button>CHECKOUT</button>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Arrows -->
        <div class="arrows">
            <button id="prev">&lt;</button>
            <button id="next">&gt;</button>
            <button id="back">See All &#8599;</button>
        </div>
    </div>
</section>


        <!-- Categories Section - Staggered Scale Animation -->
<section class="categories-section" id="categories-section">
    <div class="carousel-title">
        <h2 class="hero-title text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight mb-4">
            Our Categories
        </h2>
    </div>
    
    <div class="category-grid">
        <a href="mountain.php" class="category-card hover-card" style="background-image: url('mountain.jpg');">
            <span>Mountain</span>
        </a>
        
        <a href="gravel.php" class="category-card hover-card" style="background-image: url('gravel.jpg');">
            <span>Gravel</span>
        </a>
        
        <a href="road.php" class="category-card hover-card" style="background-image: url('road.jpg');">
            <span>Road</span>
        </a>
        
        <a href="ch.php" class="category-card hover-card" style="background-image: url('city-hybrid.jpg');">
            <span>City & Hybrid</span>
        </a>
        
        <a href="kids.php" class="category-card hover-card" style="background-image: url('kids.jpg');">
            <span>Kids</span>
        </a>
        
        <a href="ebikes.php" class="category-card hover-card" style="background-image: url('ebikes.jpg');">
            <span>E-Bikes</span>
        </a>
        
        <a href="accessories.php" class="category-card hover-card" style="background-image: url('accessories.jpg');">
            <span>Accessories</span>
        </a>
        
        <a href="clothing.php" class="category-card hover-card" style="background-image: url('clothing.jpg');">
            <span>Clothing</span>
        </a>
    </div>
</section>

        <!-- Features Section - Scale Animation -->
       <section class="bg-black text-white py-16 px-6 duration-1000" data-delay="0">
    <div class="max-w-7xl mx-auto text-center">
        <div class="carousel-title" data-delay="200">
            <h2 class="hero-title text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight mb-4">
                WHAT MAKES US SPECIAL
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <div class="bg-[#0f0f0f] p-6 rounded-xl shadow-lg transform transition-all duration-500 hover:scale-110 hover:shadow-cyan-400/30 hover:-translate-y-2" data-delay="0">
                <h3 class="text-cyan-300 font-bold text-xl mb-3">⚡ Carbon Fiber Frame</h3>
                <p class="text-gray-400">Ultra-light and durable, offering unmatched speed and shock absorption on any terrain.</p>
            </div>
            
            <div class="bg-[#0f0f0f] p-6 rounded-xl shadow-lg transform transition-all duration-500 hover:scale-110 hover:shadow-cyan-400/30 hover:-translate-y-2" data-delay="150">
                <h3 class="text-cyan-300 font-bold text-xl mb-3">📱 Smart Ride Technology</h3>
                <p class="text-gray-400">Track your speed, distance, calories, and GPS with our integrated mobile app system.</p>
            </div>
            
            <div class="bg-[#0f0f0f] p-6 rounded-xl shadow-lg transform transition-all duration-500 hover:scale-110 hover:shadow-cyan-400/30 hover:-translate-y-2" data-delay="300">
                <h3 class="text-cyan-300 font-bold text-xl mb-3">🚀 Aero-Dynamic Design</h3>
                <p class="text-gray-400">Crafted for wind resistance reduction—ride faster with less effort.</p>
            </div>
            
            <div class="bg-[#0f0f0f] p-6 rounded-xl shadow-lg transform transition-all duration-500 hover:scale-110 hover:shadow-cyan-400/30 hover:-translate-y-2" data-delay="450">
                <h3 class="text-cyan-300 font-bold text-xl mb-3">🔋 Long Battery Life (E-Bikes)</h3>
                <p class="text-gray-400">Up to 100 km on a single charge with fast recharge support.</p>
            </div>
            
            <div class="bg-[#0f0f0f] p-6 rounded-xl shadow-lg transform transition-all duration-500 hover:scale-110 hover:shadow-cyan-400/30 hover:-translate-y-2" data-delay="600">
                <h3 class="text-cyan-300 font-bold text-xl mb-3">🛠️ Custom Build Options</h3>
                <p class="text-gray-400">Choose your frame, gear set, brakes & saddle – built to your ride style.</p>
            </div>
            
            <div class="bg-[#0f0f0f] p-6 rounded-xl shadow-lg transform transition-all duration-500 hover:scale-110 hover:shadow-cyan-400/30 hover:-translate-y-2" data-delay="750">
                <h3 class="text-cyan-300 font-bold text-xl mb-3">💳 Flexible EMI & Warranty</h3>
                <p class="text-gray-400">Buy now, pay later. Includes a 1-year warranty & lifetime frame support.</p>
            </div>
        </div>
    </div>
</section>


        <!-- FAQ Section - Slide In Right -->
       <section id="faq" class="bg-black py-12 md:py-24 duration-1000" data-delay="0">
    <div class="container mx-auto px-6">
        <div class="carousel-title animate-element zoom-in" data-delay="200">
            <h2 class="hero-title text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight mb-4">
                FREQUENTLY ASKED QUESTIONS
            </h2>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            <div class="faq-item" data-delay="0">
                <div class="faq-question" onclick="toggleFAQ(0)">
                    <h3 class="font-orbitron text-lg tracking-wider">
                        Q1. What type of bicycles does Alpha Fuel offer?
                    </h3>
                    <svg class="w-6 h-6 text-cyan-400 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="faq-answer" id="faq-0">
                    <p class="text-gray-400">
                        We offer a wide range including road, mountain, ebikes, electric, and ebikes' cycles tailored for performance and comfort.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-delay="100">
                <div class="faq-question" onclick="toggleFAQ(1)">
                    <h3 class="font-orbitron text-lg tracking-wider">
                        Q2. Can I return or exchange a cycle after purchase?
                    </h3>
                    <svg class="w-6 h-6 text-cyan-400 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="faq-answer" id="faq-1">
                    <p class="text-gray-400">
                        Yes, within 7 days of delivery if unused and in original packaging. Check our Returns & Exchanges policy for more.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-delay="200">
                <div class="faq-question" onclick="toggleFAQ(2)">
                    <h3 class="font-orbitron text-lg tracking-wider">
                        Q3. Do you offer home delivery and assembly services?
                    </h3>
                    <svg class="w-6 h-6 text-cyan-400 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="faq-answer" id="faq-2">
                    <p class="text-gray-400">
                        Yes! We deliver pan-India and include tools for assembly. Technician setup is also available in select locations.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-delay="300">
                <div class="faq-question" onclick="toggleFAQ(3)">
                    <h3 class="font-orbitron text-lg tracking-wider">
                        Q4. Do you offer EMI or financing options?
                    </h3>
                    <svg class="w-6 h-6 text-cyan-400 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="faq-answer" id="faq-3">
                    <p class="text-gray-400">
                        Yes! Flexible EMI and Buy Now, Pay Later options are available with major providers at checkout.
                    </p>
                </div>
            </div>

            <div class="faq-item" data-delay="400">
                <div class="faq-question" onclick="toggleFAQ(4)">
                    <h3 class="font-orbitron text-lg tracking-wider">
                        Q5. Do Alpha Fuel cycles come with a warranty?
                    </h3>
                    <svg class="w-6 h-6 text-cyan-400 faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="faq-answer" id="faq-4">
                    <p class="text-gray-400">
                        Yes, every Alpha Fuel cycle includes a 1-year frame warranty and 6 months on components.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


        <!-- Testimonials Section - Slide Up -->
        <section class="bg-black py-16 px-6 duration-1200" data-delay="0">
    <div class="max-w-7xl mx-auto text-center">
        <div class="carousel-title animate-element zoom-in" data-delay="200">
            <h2 class="hero-title text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight mb-4">
                WHAT OUR CUSTOMERS SAY
            </h2>
        </div>

        <div class="relative">
            <button class="absolute left-0 top-1/2 transform -translate-y-1/2 z-10 bg-cyan-400 text-black p-3 rounded-full hover:bg-cyan-300 transition-all duration-300 hover:scale-110 hover:shadow-lg hover:shadow-cyan-400/50" onclick="slideTestimonials(-1)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
            </button>

            <div class="overflow-hidden">
                <div class="testimonial-track" id="testimonialTrack">
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Amazing bikes and fast delivery!"</p>
                            <p class="text-cyan-400 font-bold">- Arjun</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Customer service is top-notch."</p>
                            <p class="text-cyan-400 font-bold">- Neha</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Love the design and comfort."</p>
                            <p class="text-cyan-400 font-bold">- Rishi</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Highly recommended to beginners."</p>
                            <p class="text-cyan-400 font-bold">- Priya</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Super smooth ride!"</p>
                            <p class="text-cyan-400 font-bold">- Karan</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Stylish and affordable."</p>
                            <p class="text-cyan-400 font-bold">- Anika</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"The ebikes' collection is great!"</p>
                            <p class="text-cyan-400 font-bold">- Kiran</p>
                        </div>
                    </div>
                    <div class="testimonial-card">
                        <div class="testimonial-inner">
                            <p class="text-white mb-4">"Support team helped a lot."</p>
                            <p class="text-cyan-400 font-bold">- Anjali</p>
                        </div>
                    </div>
                </div>
            </div>

            <button class="absolute right-0 top-1/2 transform -translate-y-1/2 z-10 bg-cyan-400 text-black p-3 rounded-full hover:bg-cyan-300 transition-all duration-300 hover:scale-110 hover:shadow-lg hover:shadow-cyan-400/50" onclick="slideTestimonials(1)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                </svg>
            </button>
        </div>
    </div>
</section>
<section id="cycle-elements" class="relative bg-black text-white py-24 px-6 overflow-hidden">
    <div class="absolute inset-0 -z-10 opacity-20">
    <img src="bg.png" alt="Cycle parts background" class="w-full h-full object-cover" />
  </div>

    <div class="max-w-5xl mx-auto text-center relative z-10">
    <h2 class="text-4xl md:text-5xl font-orbitron font-bold tracking-wide leading-tight mb-6 text-cyan-300">
      Premium Cycle Elements
    </h2>
    
    <p class="text-gray-400 text-lg max-w-3xl mx-auto mb-6">
      Upgrade your ride with our exclusive range of cycle parts and accessories. 
      From lightweight carbon frames and precision gear systems to advanced brakes, 
      saddles, and neon wheel rims — each element is crafted to enhance your 
      performance and style.
    </p>

    <p class="text-gray-500 text-base max-w-2xl mx-auto mb-10">
      Whether you’re a casual rider or a pro cyclist, our tailor-made components 
      give your bike the edge it deserves. Build, customize, and ride with confidence — 
      powered by innovation and design.
    </p>

    <a href="index.php#categories-section"
       class="inline-block bg-cyan-500 text-black font-bold py-3 px-8 rounded-full shadow-lg 
    hover:bg-cyan-400 hover:shadow-cyan-500/50 transition duration-300">
      SHOP NOW
    </a>
  </div>

    <div class="absolute inset-0 border-2 border-cyan-500/30 rounded-3xl pointer-events-none"></div>
</section>





    <div class="border-t border-cyan-800 mt-12 pt-8">

        <!-- Footer - Multi-directional Animation -->
<footer class="bg-black py-16 animate-fadeIn">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12">
            
            <!-- Sign Up and Save -->
            <div class="lg:col-span-1">
                <h4 class="text-lg font-bold text-cyan-400 mb-4 uppercase tracking-wide">Sign Up and Save</h4>
                <p class="text-gray-400 text-sm mb-6">Subscribe to get special offers, free giveaways, and exclusive deals.</p>
                
                <form class="mb-6">
                    <div class="flex">
                        <input type="email" placeholder="Enter your email" 
                               class="flex-1 bg-gray-900 text-gray-300 px-4 py-3 rounded-l-lg border border-cyan-500 focus:ring-1 focus:ring-cyan-400 focus:border-cyan-400 transition-colors text-sm">
                        <button type="submit" class="bg-cyan-500 text-black px-6 py-3 rounded-r-lg hover:bg-cyan-400 hover:scale-105 transform transition-transform">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                
                <div class="flex space-x-4">
                    <a href="#" aria-label="Facebook" class="text-gray-400 hover:text-cyan-400 transition-colors text-lg">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" aria-label="Twitter" class="text-gray-400 hover:text-cyan-400 transition-colors text-lg">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" aria-label="Pinterest" class="text-gray-400 hover:text-cyan-400 transition-colors text-lg">
                        <i class="fab fa-pinterest"></i>
                    </a>
                    <a href="#" aria-label="Instagram" class="text-gray-400 hover:text-cyan-400 transition-colors text-lg">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="text-gray-400 hover:text-cyan-400 transition-colors text-lg">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-lg font-bold text-cyan-400 mb-4 uppercase tracking-wide">Company</h4>
                <ul class="space-y-3">
                    <li><a href="mountain.php" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Mountain</a></li>
                    <li><a href="road.php" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Road</a></li>
                    <li><a href="ebikes.php" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">E-bikes</a></li>
                    <li><a href="kids.php" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Kids</a></li>
                    <li><a href="gravel.php" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Gravel</a></li>
                    <li><a href="ch.php" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">City</a></li>
                </ul>
            </div>

            <!-- Shop -->
            <div>
                <h4 class="text-lg font-bold text-cyan-400 mb-4 uppercase tracking-wide">Shop</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Brands</a></li>
                    <li><a href="index.php#categories-section" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Collections</a></li>
                    <li><a href="index.php#carousel-section" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Top Products</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Sale</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h4 class="text-lg font-bold text-cyan-400 mb-4 uppercase tracking-wide">Customer Service</h4>
                <ul class="space-y-3">
                    <li><a href="shipping.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Testimonial</a></li>
                    <li><a href="refund.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Our Factor</a></li>
                    <li><a href="index.php#faq" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">FAQ</a></li>
                    <li><a href="contact.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Contact Us</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="text-lg font-bold text-cyan-400 mb-4 uppercase tracking-wide">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="terms.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Terms of Service</a></li>
                    <li><a href="refund.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Refund Policy</a></li>
                    <li><a href="privacy.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Privacy Policy</a></li>
                    <li><a href="shipping.html" class="text-gray-400 hover:text-cyan-300 transition-colors text-sm">Shipping Policy</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom section with logo -->
        <div class="border-t border-cyan-800 mt-12 pt-8">
            <div class="text-center">
                <div class="text-4xl font-serif font-bold text-cyan-400 mb-4">ALFA CYCLES</div>
                <p class="text-gray-500 text-sm">© 2025 All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>    
    </div>

    <script>
        // Animation System
        let currentTestimonialIndex = 0;
        let openFAQIndex = null;

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '100px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const delay = parseInt(element.dataset.delay) || 0;
                    
                    setTimeout(() => {
                        element.classList.add('visible');
                    }, delay);
                    
                    observer.unobserve(element);
                }
            });
        }, observerOptions);

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Observe all animated elements
            const animatedElements = document.querySelectorAll('.animate-element');
            animatedElements.forEach(element => {
                observer.observe(element);
            });

            // Initialize user menu
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');

            if (userMenuBtn) {
                userMenuBtn.addEventListener('click', () => {
                    userDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!userMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });

        // FAQ Toggle Function
        function toggleFAQ(index) {
            const faqAnswer = document.getElementById(`faq-${index}`);
            const faqIcon = document.querySelectorAll('.faq-icon')[index];
            
            // Close all other FAQs
            document.querySelectorAll('.faq-answer').forEach((answer, i) => {
                if (i !== index) {
                    answer.classList.remove('open');
                    document.querySelectorAll('.faq-icon')[i].classList.remove('rotated');
                }
            });
            
            // Toggle current FAQ
            if (openFAQIndex === index) {
                faqAnswer.classList.remove('open');
                faqIcon.classList.remove('rotated');
                openFAQIndex = null;
            } else {
                faqAnswer.classList.add('open');
                faqIcon.classList.add('rotated');
                openFAQIndex = index;
            }
        }

        // Testimonial Slider Function
        function slideTestimonials(direction) {
            const track = document.getElementById('testimonialTrack');
            const totalTestimonials = 8;
            const maxIndex = totalTestimonials - 4;
            
            currentTestimonialIndex += direction;
            
            if (currentTestimonialIndex < 0) {
                currentTestimonialIndex = maxIndex;
            } else if (currentTestimonialIndex > maxIndex) {
                currentTestimonialIndex = 0;
            }
            
            const translateX = -(currentTestimonialIndex * 25);
            track.style.transform = `translateX(${translateX}%)`;
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll-based header background
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.backgroundColor = 'rgba(0, 0, 0, 0.95)';
            } else {
                header.style.backgroundColor = 'rgba(0, 0, 0, 0.9)';
            }
        });

        // Carousel Functionality
        let nextButton = document.getElementById('next');
        let prevButton = document.getElementById('prev');
        let carousel = document.querySelector('.carousel');
        let listHTML = document.querySelector('.carousel .list');
        let seeMoreButtons = document.querySelectorAll('.seeMore');
        let backButton = document.getElementById('back');

        nextButton.onclick = function(){
            showSlider('next');
        }

        prevButton.onclick = function(){
            showSlider('prev');
        }

        let unAcceptClick;
        const showSlider = (type) => {
            nextButton.style.pointerEvents = 'none';
            prevButton.style.pointerEvents = 'none';

            carousel.classList.remove('next', 'prev');
            let items = document.querySelectorAll('.carousel .list .item');
            
            if(type === 'next'){
                listHTML.appendChild(items[0]);
                carousel.classList.add('next');
            } else {
                listHTML.prepend(items[items.length - 1]);
                carousel.classList.add('prev');
            }
            
            clearTimeout(unAcceptClick);
            unAcceptClick = setTimeout(() => {
                nextButton.style.pointerEvents = 'auto';
                prevButton.style.pointerEvents = 'auto';
            }, 2000);
        }

        seeMoreButtons.forEach((button) => {
            button.onclick = function(){
                carousel.classList.remove('next', 'prev');
                carousel.classList.add('showDetail');
            }
        });

        backButton.onclick = function(){
            carousel.classList.remove('showDetail');
        }

        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                showSlider('prev');
            } else if (e.key === 'ArrowRight') {
                showSlider('next');
            } else if (e.key === 'Escape') {
                carousel.classList.remove('showDetail');
            }
        });

        // Add touch/swipe support for mobile
        let startX = 0;
        let endX = 0;

        carousel.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });

        carousel.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = startX - endX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    showSlider('next');
                } else {
                    // Swipe right - previous slide
                    showSlider('prev');
                }
            }
        }
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
    </script>
</body>
</html>
