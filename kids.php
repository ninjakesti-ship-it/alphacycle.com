<?php
session_start();
include 'db_connect.php';

// Pagination
$limit = 9;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Totals
$total_items = 0;
$total_pages = 1;
if ($countRes = $conn->query("SELECT COUNT(*) AS total FROM `kids`")) {
    $countRow = $countRes->fetch_assoc();
    $total_items = (int)($countRow['total'] ?? 0);
    $total_pages = max(1, (int)ceil($total_items / $limit));
    $countRes->close();
} else {
    error_log('DB error in kids count: ' . $conn->error);
}

// Cart count (kept in case you use it elsewhere)
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Kids Bikes - AlphaCycles</title>

  <link rel="stylesheet" href="j.css">
  <link rel="stylesheet" href="categories.css">
  <link rel="stylesheet" href="footer.css">
  <link rel="stylesheet" href="ebike-style.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500;70s0&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
  <!-- Lottie Player script added here -->
  <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script>

  <style>
/* Grid: 3 cards per row on desktop, 2 on tablets, 1 on mobile */
.product-grid {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 24px;
  width: 100%;
  max-width: 1200px;
  margin: 24px auto 48px;
  padding: 0 16px;
}

/* Tablet */
@media (max-width: 1024px) {
  .product-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px; }
}

/* Mobile */
@media (max-width: 640px) {
  .product-grid { grid-template-columns: 1fr; gap: 16px; }
}

/* Product Card */
.card {
  position: relative;
  background: linear-gradient(145deg, #0f0f0f 0%, #1a1a1a 100%);
  border: 1px solid rgba(0, 255, 255, 0.15);
  border-radius: 16px;
  color: #fff;
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  padding: 14px;
  display: flex;
  flex-direction: column;
  min-height: 100%;
}
.card:hover {
  transform: translateY(-4px);
  border-color: rgba(0, 255, 255, 0.35);
  box-shadow: 0 10px 28px rgba(0, 255, 255, 0.12), 0 6px 10px rgba(0,0,0,0.3);
}

/* Image */
.card img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 12px;
  background: #0b0b0b;
  border: 1px solid rgba(255,255,255,0.06);
}

/* Title */
.card h3 {
  font-size: 1.05rem;
  font-weight: 700;
  margin: 12px 2px 4px;
  letter-spacing: 0.2px;
  line-height: 1.35;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Price */
.card .price {
  color: #00ffaa;
  font-weight: 800;
  letter-spacing: 0.2px;
  margin: 2px 2px 8px;
}

/* Stock row */
.card .stock {
  font-size: 0.9rem;
  color: #c9d4da;
  margin: 4px 2px 10px;
  display: flex;
  align-items: center;
  gap: 6px;
}

/* Stock state colors */
.stock-high { color: #9fffe0 !important; }
.stock-medium { color: #ffd28c !important; }
.stock-low { color: #ff9b9b !important; font-weight: 700; }
.stock-out { color: #ff6f6f !important; font-weight: 800; }

/* Actions */
.card .actions {
  margin-top: auto;
  display: flex;
  gap: 10px;
  padding-top: 8px;
}
.card .actions .add,
.card .actions .buy {
  appearance: none;
  border: 1px solid rgba(0, 255, 255, 0.25);
  background: rgba(0, 0, 0, 0.35);
  color: #e9f9ff;
  padding: 10px 12px;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 700;
  letter-spacing: 0.3px;
  cursor: pointer;
  width: 100%;
  transition: transform 0.15s ease, background 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}
.card .actions .add:hover,
.card .actions .buy:hover {
  transform: translateY(-2px);
  border-color: rgba(0, 255, 255, 0.5);
}
.card .actions .buy {
  background: linear-gradient(135deg, #00ffaa 0%, #00c98d 100%);
  color: #000;
  border: 1px solid rgba(0, 0, 0, 0.6);
}
.card .actions button[disabled],
.card .actions .add[disabled],
.card .actions .buy[disabled] {
  opacity: 0.55;
  cursor: not-allowed;
  transform: none;
}

/* Out-of-stock overlay */
.out-of-stock-overlay {
  position: absolute; inset: 0; background: rgba(0,0,0,0.8);
  display: flex; align-items: center; justify-content: center;
  border-radius: 16px; z-index: 10;
}
.out-of-stock-text {
  color: #ff4444; font-size: 1.5rem; font-weight: bold; font-family: 'Orbitron', sans-serif; text-align: center;
  text-shadow: 0 0 10px rgba(255,68,68,0.5);
}

/* Pagination */
.pagination {
  display: flex; align-items: center; justify-content: center;
  gap: 8px; padding: 8px 0 24px;
}
.pagination a, .pagination .prev-next {
  color: #e9f9ff; background: rgba(0,0,0,0.35);
  border: 1px solid rgba(0,255,255,0.25);
  padding: 8px 12px; border-radius: 10px; text-decoration: none;
  font-weight: 600; transition: transform 0.15s ease, border-color 0.2s ease, background 0.2s ease;
}
.pagination a:hover, .pagination .prev-next:hover {
  transform: translateY(-2px); border-color: rgba(0,255,255,0.5);
}
.pagination a.active {
  background: linear-gradient(135deg, #00ffaa 0%, #00c98d 100%);
  color: #000; border-color: transparent;
}
.pagination-dots { color: #a9b8bf; padding: 0 4px; }

/* Page info */
.page-info {
  color: #c9d4da;
  text-align: center;
  margin: 8px auto 0;
  font-size: 0.95rem;
}



/* Subtle pulse on update */
.real-time-update { animation: pulse-update 0.5s ease-in-out; }
@keyframes pulse-update { 0%{transform:scale(1)} 50%{transform:scale(1.05); background-color:rgba(0,255,255,0.1)} 100%{transform:scale(1)} }
  </style>
</head>
<body class="bg-black text-white">
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
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="index.php#categories-section">BIKES</a>
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="about.html">ABOUT US</a>
                <a class="nav-link hover:text-cyan-400 tracking-widest transition-colors duration-300" href="contact.php">CONTACT US</a>
                
                <!-- Cart -->
                <a href="cart2.php" class="cart-button text-2xl relative hover:text-cyan-400 transition-colors duration-300">
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
                    Unbeatable kids<br> 
                    Deals Now Delivered<br> 
                    Your Way
                </h1>
                <p class="mt-6 text-lg text-gray-300 max-w-md">
                    Choose in-store pickup, fast home delivery, or expert assembly right at your doorstep.
                </p>
               <a href="#products" class="glow-button mt-6 inline-block">
    Explore Products
</a>
            </div>
            
            <!-- Right Content - Animation -->
            <div class="flex justify-center loading">
                <dotlottie-player
                    src="https://lottie.host/b6381256-eb8e-4f40-928e-3303f10f87a4/eUrBXjNvdV.lottie" 
                    style="width: 500px; height: 500px" 
                    speed="1" 
                    autoplay 
                    loop>
                </dotlottie-player>
            </div>
        </div>
    </section>

<section id="products" class="pt-8">
  <h2 class="text-center text-3xl font-orbitron font-bold mb-4">Our Kids Bikes Collection</h2>

  <?php if ($total_items > 0): ?>
    <div class="page-info">
      Showing <?= (($page - 1) * $limit) + 1 ?> - <?= min($page * $limit, $total_items) ?> of <?= $total_items ?> kids bikes
      <span class="text-cyan-400 ml-4">📊 Live Stock Updates</span>
    </div>
  <?php endif; ?>

    <div class="product-grid">
  <?php
    $safeLimit  = (int)$limit;
    $safeOffset = (int)$offset;
    // ✅ Include rating column in SELECT
    $sql = "SELECT id, name, price, stock, image, rating FROM `mountain` ORDER BY id ASC LIMIT $safeLimit OFFSET $safeOffset";
    $result = $conn->query($sql);

    if ($result === false) {
      error_log('DB error in mountain list: ' . $conn->error);
      echo '<div class="no-products"><p>⚠️ Unable to load mountain right now. Please try again later.</p></div>';
    } elseif ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $product_id   = (int)$row['id'];
        $product_name = htmlspecialchars($row['name'] ?? '');
        $product_price = (float)($row['price'] ?? 0);
        $product_stock = (int)($row['stock'] ?? 0);
        $product_image = htmlspecialchars($row['image'] ?? '');
        $product_rating = (int)($row['rating'] ?? 0); // ✅ Added rating

        $is_out_of_stock = $product_stock <= 0;
        $stock_class = 'stock-high';
        if ($product_stock <= 0) { $stock_class = 'stock-out'; }
        elseif ($product_stock <= 5) { $stock_class = 'stock-low'; }
        elseif ($product_stock <= 10) { $stock_class = 'stock-medium'; }
  ?>
    <div class="card loading <?= $is_out_of_stock ? 'opacity-75' : '' ?>" data-product-id="<?= $product_id ?>">
      <?php if ($is_out_of_stock): ?>
        <div class="out-of-stock-overlay">
          <div class="out-of-stock-text">OUT OF<br>STOCK</div>
        </div>
      <?php endif; ?>

      <img
        src="uploads/<?= $product_image ?>"
        alt="<?= $product_name ?>"
        loading="lazy"
        onerror="this.onerror=null;this.src='uploads/placeholder.png'">

      <h3><?= $product_name ?></h3>
      <p class="price">₹<?= number_format($product_price, 2) ?></p>

      <!-- ⭐ Product Rating Display -->
      <div class="rating">
        <?php
          if ($product_rating > 0) {
            for ($i = 1; $i <= 5; $i++) {
              echo $i <= $product_rating ? "★" : "☆";
            }
          } else {
            echo "<span class='no-rating'>No Rating</span>";
          }
        ?>
      </div>
      <!-- End Rating -->

      <p class="stock <?= $stock_class ?>" data-stock-display="<?= $product_id ?>">
        Stock: <span class="stock-count"><?= $product_stock ?></span> units
      </p>

      <div class="actions">
        <form method="POST" action="add_to_cart.php" onsubmit="return handleAddToCart(event, <?= $product_id ?>)">
          <input type="hidden" name="product_id" value="<?= $product_id ?>">
          <input type="hidden" name="table" value="mountain">
          <button type="submit" class="add" <?= $is_out_of_stock ? 'disabled' : '' ?>>
            <?= $is_out_of_stock ? 'Out of Stock' : 'Add to Cart' ?>
          </button>
        </form>
        <button class="buy" onclick="buyNow(<?= $product_id ?>)" <?= $is_out_of_stock ? 'disabled' : '' ?>>
          <?= $is_out_of_stock ? 'Unavailable' : 'Buy Now' ?>
        </button>
      </div>
    </div>
  <?php
      }
      $result->free();
    } else {
      echo '<div class="no-products">
              <p>⛰️ No mountain available at the moment.</p>
              <p>Check back soon for amazing deals!</p>
            </div>';
    }
  ?>
</div>

  <?php if ($total_pages > 1): ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>#products" class="prev-next">&laquo; Previous</a>
      <?php endif; ?>

      <?php
      $start_page = max(1, $page - 2);
      $end_page   = min($total_pages, $page + 2);

      if ($start_page > 1) {
        echo '<a href="?page=1#products">1</a>';
        if ($start_page > 2) echo '<span class="pagination-dots">...</span>';
      }
      for ($i=$start_page; $i <= $end_page; $i++) {
        $active = $i == $page ? 'class="active"' : '';
        echo "<a href='?page=$i#products' $active>$i</a>";
      }
      if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) echo '<span class="pagination-dots">...</span>';
        echo "<a href='?page=$total_pages#products'>$total_pages</a>";
      }
      ?>
      <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>#products" class="prev-next">Next &raquo;</a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</section>

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


<script>
document.addEventListener('DOMContentLoaded', function() {
  initializeAnimations();
  initializeLazyLoading();
  handlePaginationScroll();
  startStockUpdateInterval();
});

function startStockUpdateInterval() { setInterval(updateAllStockInfo, 30000); }
function updateAllStockInfo() {
  document.querySelectorAll('.card[data-product-id]').forEach(card => {
    updateStockInfo(card.getAttribute('data-product-id'));
  });
}
function updateStockInfo(productId) {
  fetch(`get_stock_info.php?product_id=${productId}&table=kids`)
    .then(r => r.json())
    .then(data => { if (data.success) updateProductDisplay(productId, data.stock); })
    .catch(err => console.error('Error updating stock:', err));
}


// Updates the UI to reflect the latest stock number, removes/creates overlay and toggles buttons.
function updateProductDisplay(productId, newStockRaw) {
  var newStock = parseInt(newStockRaw, 10);
  if (!isFinite(newStock)) return;

  var card = document.querySelector('[data-product-id="' + productId + '"]');
  if (!card) return;

  var stockDisplay = card.querySelector('[data-stock-display]');
  var stockCount = card.querySelector('.stock-count');
  var addButton = card.querySelector('.add');
  var buyButton = card.querySelector('.buy');
  var overlay = card.querySelector('.out-of-stock-overlay');

  if (!stockCount) return;

  var current = (stockCount.textContent || '').trim();
  if (current === String(newStock)) return;

  if (stockDisplay) {
    stockDisplay.classList.add('real-time-update');
    setTimeout(function () {
      if (stockDisplay) stockDisplay.classList.remove('real-time-update');
    }, 500);
  }

  stockCount.textContent = newStock;

  var stockParagraph = card.querySelector('.stock');
  if (stockParagraph) {
    stockParagraph.classList.remove('stock-high', 'stock-medium', 'stock-low', 'stock-out');
  }

  var stockClass = 'stock-high';

  if (newStock <= 0) {
    stockClass = 'stock-out';
    card.classList.add('opacity-75');

    if (!overlay) {
      overlay = document.createElement('div');
      overlay.className = 'out-of-stock-overlay';
      overlay.innerHTML = '<div class="out-of-stock-text">OUT OF<br>STOCK</div>';
      card.appendChild(overlay);
    } else {
      overlay.style.display = 'flex';
    }

    if (addButton) { addButton.disabled = true; addButton.textContent = 'Out of Stock'; }
    if (buyButton) { buyButton.disabled = true; buyButton.textContent = 'Unavailable'; }
  } else {
    card.classList.remove('opacity-75');
    if (overlay) overlay.remove();

    if (addButton) { addButton.disabled = false; addButton.textContent = 'Add to Cart'; }
    if (buyButton) { buyButton.disabled = false; buyButton.textContent = 'Buy Now'; }

    if (newStock <= 5) stockClass = 'stock-low';
    else if (newStock <= 10) stockClass = 'stock-medium';
  }

  if (stockParagraph) stockParagraph.classList.add(stockClass);
}

// Adds to cart after confirming stock; refreshes UI and cart count.
function handleAddToCart(event, productId) {
  event.preventDefault();

  fetch('get_stock_info.php?product_id=' + productId + '&table=kids')
    .then(function (r) { return r.json(); })
    .then(function (stockData) {
      if (!stockData || !stockData.success || parseInt(stockData.stock, 10) <= 0) {
        showNotification('This item is currently out of stock!', 'error');
        updateStockInfo(productId);
        return;
      }
      var formData = new FormData(event.target);
      fetch('add_to_cart.php', { method: 'POST', body: formData })
        .then(function (r) { return r.text(); })
        .then(function () {
          showNotification('Product added to cart successfully!', 'success');
          updateCartCount();

          var btn = event.target.querySelector('button[type="submit"]');
          if (btn) {
            var original = btn.textContent;
            btn.textContent = 'Added!';
            btn.style.background = 'linear-gradient(135deg, #00ffaa, #00c98d)';
            setTimeout(function () {
              btn.textContent = original;
              btn.style.background = '';
            }, 2000);
          }

          setTimeout(function () { updateStockInfo(productId); }, 500);
        })
        .catch(function () {
          showNotification('Error adding product to cart. Please try again.', 'error');
        });
    })
    .catch(function () {
      showNotification('Error checking stock. Please try again.', 'error');
    });

  return false;
}

// Adds to cart then navigates to checkout; confirms stock first.
function buyNow(productId) {
  fetch('get_stock_info.php?product_id=' + productId + '&table=kids')
    .then(function (r) { return r.json(); })
    .then(function (stockData) {
      if (!stockData || !stockData.success || parseInt(stockData.stock, 10) <= 0) {
        showNotification('This item is currently out of stock!', 'error');
        updateStockInfo(productId);
        return;
      }
      showNotification('Redirecting to checkout...', 'success');
      var fd = new FormData();
      fd.append('product_id', productId);
      fd.append('table', 'kids');
      fetch('add_to_cart.php', { method: 'POST', body: fd })
        .then(function () { setTimeout(function () { window.location.href = 'checkout.php'; }, 1000); })
        .catch(function () { showNotification('Error processing request. Please try again.', 'error'); });
    })
    .catch(function () { showNotification('Error checking stock. Please try again.', 'error'); });
}

// Simple toast; safe if GSAP not present.
function showNotification(message, type) {
  if (type === void 0) { type = 'success'; }
  var el = document.createElement('div');
  el.className = 'notification ' + type;
  el.textContent = message;
  if (type === 'warning') {
    el.style.background = 'linear-gradient(135deg, #ffaa00, #ff8800)';
    el.style.color = '#000';
  }
  document.body.appendChild(el);
  setTimeout(function () {
    if (window.gsap) {
      gsap.to(el, { x: '100%', opacity: 0, duration: 0.3, onComplete: function () { el.remove(); } });
    } else {
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 300);
    }
  }, 4000);
}

// Animations
function initializeAnimations() {
  if (window.gsap && window.ScrollTrigger) {
    gsap.from('.card', {
      duration: 0.8, y: 50, opacity: 0, stagger: 0.15,
      scrollTrigger: { trigger: '#products', start: 'top 80%', end: 'bottom 20%', toggleActions: 'play none none reverse' }
    });
  }
}

// Lazy load support (safe even if you don't use data-src)
function initializeLazyLoading() {
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var img = entry.target;
          if (img.dataset && img.dataset.src) {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
            io.unobserve(img);
          }
        }
      });
    });
    Array.prototype.forEach.call(document.querySelectorAll('img[data-src]'), function (img) { io.observe(img); });
  }
}

// Smooth jump to products on paginated pages
function handlePaginationScroll() {
  var urlParams = new URLSearchParams(window.location.search);
  var currentPage = urlParams.get('page');
  if ((currentPage && parseInt(currentPage, 10) > 1) || window.location.hash === '#products') {
    setTimeout(function () {
      var el = document.getElementById('products');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 300);
  }
}

// Updates the cart badge if a header exists on other pages.
function updateCartCount() {
  fetch('get_cart_count.php')
    .then(function (r) { return r.json(); })
    .then(function (data) {
      var badge = document.querySelector('.badge');
      var cartButton = document.querySelector('.cart-button');
      if (!cartButton) return;
      var count = parseInt((data && data.count) || 0, 10) || 0;
      if (count > 0) {
        if (badge) {
          badge.textContent = count;
          if (window.gsap) { gsap.fromTo(badge, { scale: 1.5 }, { scale: 1, duration: 0.3, ease: 'back.out(1.7)' }); }
        } else {
          var newBadge = document.createElement('span');
          newBadge.className = 'badge absolute top-[-10px] right-[-10px] bg-cyan-400 text-black text-xs px-2 py-0.5 rounded-full';
          newBadge.textContent = count;
          cartButton.appendChild(newBadge);
          if (window.gsap) { gsap.fromTo(newBadge, { scale: 0, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.4, ease: 'back.out(1.7)' }); }
        }
      } else if (badge) {
        if (window.gsap) { gsap.to(badge, { scale: 0, opacity: 0, duration: 0.3, onComplete: function () { badge.remove(); } }); }
        else { badge.remove(); }
      }
    })
    .catch(function () { /* ignore badge errors */ });
}
</script>
</body>
</html>
<?php $conn->close(); ?>
