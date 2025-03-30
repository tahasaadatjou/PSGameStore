<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "PlayStation Game Store - Next-Gen Gaming Experience";
$meta_description = "Discover the ultimate PlayStation experience with exclusive PS5 & PS4 games, sleek consoles, and cutting-edge accessories.";

include 'includes/header.php';

$featured_products = [];
$new_releases = [];
$deals = [];
$error = null;

try {
    $stmt = $GLOBALS['pdo']->query("SELECT p.*, (SELECT AVG(rating) FROM reviews WHERE product_id = p.id) as avg_rating FROM products p WHERE featured = 1 ORDER BY created_at DESC LIMIT 8");
    $featured_products = $stmt->fetchAll();

    $stmt = $GLOBALS['pdo']->query("SELECT * FROM products WHERE release_date BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ORDER BY release_date DESC LIMIT 6");
    $new_releases = $stmt->fetchAll();

    $stmt = $GLOBALS['pdo']->query("SELECT * FROM products WHERE discount > 0 ORDER BY discount DESC LIMIT 4");
    $deals = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $error = "Unable to load products right now. Please try again later.";
}
?>

<main class="main-content">
    <section class="hero-section">
        <div class="hero-slider" id="hero-slider">
            <div class="slide active" style="background-image: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.2)), url('assets/images/hero-1.jpg');">
                <div class="slide-content">
                    <h1 class="slide-title">Next-Gen PS5 Exclusives</h1>
                    <p class="slide-text">Unleash the power of next-gen gaming</p>
                    <a href="games.php?platform=ps5" class="btn btn-gradient">Explore Now</a>
                </div>
            </div>
            <div class="slide" style="background-image: linear-gradient(to right, rgba(0,0,0,0.6), rgba(0,0,0,0.2)), url('assets/images/hero-2.jpg');">
                <div class="slide-content">
                    <h1 class="slide-title">Epic Summer Sale</h1>
                    <p class="slide-text">Save up to 60% on top titles</p>
                    <a href="deals.php" class="btn btn-gradient">Grab Deals</a>
                </div>
            </div>
        </div>
        <div class="slider-nav">
            <button class="slider-btn prev" aria-label="Previous Slide">❮</button>
            <div class="slider-dots"></div>
            <button class="slider-btn next" aria-label="Next Slide">❯</button>
        </div>
    </section>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <section class="products-section featured">
        <h2 class="section-title">Featured Games</h2>
        <div class="products-grid">
            <?php if (empty($featured_products)): ?>
                <p class="no-items">No featured games available right now.</p>
            <?php else: ?>
                <?php foreach ($featured_products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 loading="lazy">
                            <?php if ($product['discount'] > 0): ?>
                                <span class="badge discount"><?php echo $product['discount']; ?>% OFF</span>
                            <?php elseif (strtotime($product['release_date']) > strtotime('-30 days')): ?>
                                <span class="badge new">NEW</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-details">
                            <h3 class="product-name">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h3>
                            <div class="product-meta">
                                <span class="platform ps5">PS5</span>
                                <?php if ($product['ps4_compatible']): ?>
                                    <span class="platform ps4">PS4</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?php echo $i <= round($product['avg_rating']) ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                                <span class="rating-count">(<?php echo getReviewCount($product['id']); ?>)</span>
                            </div>
                            <div class="product-price">
                                <?php if ($product['discount'] > 0): ?>
                                    <span class="price-old">$<?php echo number_format($product['price'], 2); ?></span>
                                    <span class="price-new">$<?php echo calculateDiscountPrice($product['price'], $product['discount']); ?></span>
                                <?php else: ?>
                                    <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                            <button class="btn btn-add" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="products-section new-releases">
        <div class="section-header">
            <h2 class="section-title">New Releases</h2>
            <a href="new-releases.php" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            <?php if (empty($new_releases)): ?>
                <p class="no-items">No new releases available right now.</p>
            <?php else: ?>
                <?php foreach ($new_releases as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 loading="lazy">
                        </div>
                        <div class="product-details">
                            <h3 class="product-name">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h3>
                            <div class="product-price">
                                <span class="price">$<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                            <button class="btn btn-add" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="products-section deals">
        <div class="section-header">
            <h2 class="section-title">Hot Deals</h2>
            <a href="deals.php" class="view-all">View All</a>
        </div>
        <div class="products-grid">
            <?php if (empty($deals)): ?>
                <p class="no-items">No deals available right now.</p>
            <?php else: ?>
                <?php foreach ($deals as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 loading="lazy">
                            <span class="badge discount"><?php echo $product['discount']; ?>% OFF</span>
                        </div>
                        <div class="product-details">
                            <h3 class="product-name">
                                <a href="product.php?id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h3>
                            <div class="product-price">
                                <span class="price-old">$<?php echo number_format($product['price'], 2); ?></span>
                                <span class="price-new">$<?php echo calculateDiscountPrice($product['price'], $product['discount']); ?></span>
                            </div>
                            <button class="btn btn-add" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="newsletter-section">
        <div class="newsletter-content">
            <h3 class="newsletter-title">Stay in the Game</h3>
            <p>Subscribe for exclusive offers, updates, and early access.</p>
            <form action="newsletter.php" method="post" class="newsletter-form">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="btn btn-gradient">Subscribe</button>
            </form>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>