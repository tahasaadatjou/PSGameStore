document.addEventListener('DOMContentLoaded', () => {
    // Slider
    const slides = document.querySelectorAll('.slide');
    const dotsContainer = document.querySelector('.slider-dots');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    let currentSlide = 0;

    if (slides.length > 0) {
        slides.forEach((_, i) => {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (i === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function goToSlide(index) {
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');
            currentSlide = (index + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');
        }

        prevBtn.addEventListener('click', () => goToSlide(currentSlide - 1));
        nextBtn.addEventListener('click', () => goToSlide(currentSlide + 1));
        setInterval(() => goToSlide(currentSlide + 1), 5000);
    }

    // Mobile Menu
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');
    if (menuToggle && nav) {
        menuToggle.addEventListener('click', () => {
            nav.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }
});

// Alert Function
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) {
        console.error('Alert container not found in the DOM!');
        return;
    }

    const alert = document.createElement('div');
    alert.classList.add('alert', type);

    const icon = type === 'success' 
        ? '<svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>'
        : '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>';

    alert.innerHTML = `${icon} ${message}`;
    alertContainer.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Add to Cart
function addToCart(productId) {
    fetch('includes/add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.cart-count').textContent = data.cartCount;
            showAlert('Item added to cart!', 'success');
        } else {
            showAlert(data.message || 'Failed to add item!', 'error');
        }
    })
    .catch(err => {
        console.error('Add to cart error:', err);
        showAlert('Something went wrong!', 'error');
    });
}

// Update Quantity
function updateQuantity(productId, quantity) {
    fetch('includes/update-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId, quantity })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelector('.cart-count').textContent = data.cartCount;
            showAlert('Quantity updated!', 'success');
            location.reload();
        } else {
            showAlert(data.message || 'Failed to update quantity!', 'error');
        }
    })
    .catch(err => {
        console.error('Update quantity error:', err);
        showAlert('Something went wrong!', 'error');
    });
}

// Remove Item
function removeItem(productId) {
    if (confirm('Remove this item from cart?')) {
        fetch('includes/remove-from-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector('.cart-count').textContent = data.cartCount;
                showAlert('Item removed from cart!', 'success');
                location.reload();
            } else {
                showAlert(data.message || 'Failed to remove item!', 'error');
            }
        })
        .catch(err => {
            console.error('Remove item error:', err);
            showAlert('Something went wrong!', 'error');
        });
    }
}

// Clear Cart
function clearCart() {
    if (confirm('Are you sure you want to clear your cart?')) {
        fetch('includes/clear-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector('.cart-count').textContent = data.cartCount;
                showAlert('Cart cleared!', 'success');
                location.reload();
            } else {
                showAlert(data.message || 'Failed to clear cart!', 'error');
            }
        })
        .catch(err => {
            console.error('Clear cart error:', err);
            showAlert('Something went wrong!', 'error');
        });
    }
}
