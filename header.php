<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cart_count = isset($_SESSION['cart']) 
    ? array_sum(array_column($_SESSION['cart'], 'quantity')) 
    : 0;
?>
<header class="main-header">
  <div class="logo">
    <img src="logo.png" alt="Brand Logo" />
  </div>
  <nav class="main-nav">  
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="about.html">About</a></li> 
      <li><a href="ebikes.php#products">Our Products</a></li>
      <li><a href="#faq">FAQs</a></li>
      <li><a href="#">Contact Us</a></li>
      <li><a href="login.php" class="admin-btn">Admin</a></li>
      <li>
        <a href="cart.php" class="cart-button">
          🛒
          <?php if ($cart_count > 0): ?>
            <span class="badge"><?= $cart_count ?></span>
          <?php endif; ?>
        </a>
      </li>
    </ul>
  </nav>
</header>
