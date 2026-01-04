// Cart Management System with Database Integration
document.addEventListener('DOMContentLoaded', function() {
    initializeAnimations();
    setupEventListeners();
});

function setupEventListeners() {
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', handleNewsletter);
    }
}

// Update quantity in cart
function updateQuantity(productId, table, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCart(productId, table);
        return;
    }

    showLoading(true);
    
    const formData = new FormData();
    formData.append('action', 'update_quantity');
    formData.append('product_id', productId);
    formData.append('table', table);
    formData.append('quantity', newQuantity);

    fetch('cart_operations.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showLoading(false);
        if (data.success) {
            showNotification('Quantity updated successfully!', 'success');
            // Reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error updating quantity', 'error');
        }
    })
    .catch(error => {
        showLoading(false);
        console.error('Error:', error);
        showNotification('Error updating quantity. Please try again.', 'error');
    });
}

// Remove item from cart
function removeFromCart(productId, table) {
    const cartItem = document.querySelector(`[data-product-id="${productId}"][data-table="${table}"]`);
    
    if (cartItem) {
        cartItem.classList.add('slide-out');
        
        setTimeout(() => {
            showLoading(true);
            
            const formData = new FormData();
            formData.append('action', 'remove_item');
            formData.append('product_id', productId);
            formData.append('table', table);

            fetch('cart_operations.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showLoading(false);
                if (data.success) {
                    showNotification('Item removed from cart', 'success');
                    // Reload page to reflect changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message || 'Error removing item', 'error');
                    cartItem.classList.remove('slide-out');
                }
            })
            .catch(error => {
                showLoading(false);
                console.error('Error:', error);
                showNotification('Error removing item. Please try again.', 'error');
                cartItem.classList.remove('slide-out');
            });
        }, 300);
    }
}

function proceedToCheckout() {
    window.location.href = "checkout.php";
}

function continueShopping() {
    window.location.href = "index.php";
}


// Newsletter subscription
function handleNewsletter(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;
    
    showLoading(true);
    
    setTimeout(() => {
        showLoading(false);
        showNotification(`Thanks for subscribing with ${email}!`, 'success');
        event.target.reset();
    }, 2000);
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type}`;
    notification.style.display = 'block';
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto hide after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }, 4000);
}

// Show/hide loading overlay
function showLoading(show = true) {
    const loadingOverlay = document.getElementById('loading-overlay');
    loadingOverlay.style.display = show ? 'flex' : 'none';
}

// Initialize animations
function initializeAnimations() {
    if (window.gsap) {
        gsap.from('.cart-summary', {
            duration: 0.8,
            x: 50,
            opacity: 0,
            delay: 0.3
        });

        gsap.from('.cart-item', {
            duration: 0.6,
            y: 30,
            opacity: 0,
            stagger: 0.1,
            ease: "power2.out"
        });
    }
}

// Handle keyboard navigation
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const notification = document.getElementById('notification');
        if (notification.classList.contains('show')) {
            notification.classList.remove('show');
        }
        
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay.style.display === 'flex') {
            showLoading(false);
        }
    }
});

// Smooth scrolling utility
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}
function removeFromCart(productId, table) {
    if (!confirm("Are you sure you want to remove this item?")) return;

    fetch('remove_item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}&table=${encodeURIComponent(table)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload(); // refresh cart page
        } else {
            alert('Failed to remove item. Please try again.');
        }
    })
    .catch(() => alert('Error connecting to server.'));
}
