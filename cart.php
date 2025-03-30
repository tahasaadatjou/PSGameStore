<?php
include 'includes/header.php';
require_once 'includes/config.php';
require_once 'includes/functions.php';

$cart = $_SESSION['cart'] ?? [];
$total = getCartTotal();
?>

<div class="cart-container">
    <h2 class="section-title">Your Shopping Cart</h2>
    <?php if (empty($cart)): ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="index.php" class="btn btn-gradient">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-grid">
            <?php foreach ($cart as $id => $item): 
                $subtotal = $item['price'] * $item['quantity'];
            ?>
                <div class="cart-card">
                    <div class="cart-image">
                        <img src="assets/images/products/<?php echo htmlspecialchars($item['image']); ?>" 
                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                             loading="lazy">
                    </div>
                    <div class="cart-details">
                        <h3 class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p class="cart-item-price">$<?php echo number_format($item['price'], 2); ?></p>
                        <div class="cart-quantity">
                            <label for="quantity-<?php echo $id; ?>">Qty:</label>
                            <input type="number" id="quantity-<?php echo $id; ?>" 
                                   value="<?php echo $item['quantity']; ?>" min="1" 
                                   onchange="updateQuantity(<?php echo $id; ?>, this.value)">
                        </div>
                        <p class="cart-item-subtotal">Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                        <button class="btn btn-remove" onclick="removeItem(<?php echo $id; ?>)">Remove</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <h3>Total: <span>$<?php echo number_format($total, 2); ?></span></h3>
            </div>
            <div class="cart-actions">
                <button class="btn btn-outline" onclick="clearCart()">Clear Cart</button>
                <a href="checkout.php" class="btn btn-gradient">Proceed to Checkout</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Alert Container -->
<div id="alert-container"></div>

<?php include 'includes/footer.php'; ?>