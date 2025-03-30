<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($searchQuery)) {
    try {
        $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM products WHERE name LIKE :query OR description LIKE :query ORDER BY name");
        $stmt->execute([':query' => "%$searchQuery%"]);
        $results = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Search error: " . $e->getMessage());
    }
}
?>

<div class="search-container">
    <h2 class="section-title">Search Results for "<span class="search-query"><?php echo htmlspecialchars($searchQuery); ?></span>"</h2>
    <div class="search-summary">
        <?php if (!empty($searchQuery)): ?>
            <p>Found <?php echo count($results); ?> item<?php echo count($results) !== 1 ? 's' : ''; ?></p>
        <?php endif; ?>
    </div>
    
    <?php if (empty($searchQuery)): ?>
        <div class="empty-search">
            <svg viewBox="0 0 24 24" width="60" height="60" fill="#666">
                <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <p>Please enter a search term to find your favorite games.</p>
            <a href="index.php" class="btn btn-gradient">Back to Home</a>
        </div>
    <?php elseif (empty($results)): ?>
        <div class="empty-search">
            <svg viewBox="0 0 24 24" width="60" height="60" fill="#666">
                <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <p>No results found for "<span class="search-query"><?php echo htmlspecialchars($searchQuery); ?></span>". Try something else!</p>
            <a href="index.php" class="btn btn-gradient">Back to Home</a>
        </div>
    <?php else: ?>
        <div class="search-results-grid">
            <?php foreach ($results as $product): ?>
                <div class="search-result-card">
                    <div class="search-image">
                        <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             loading="lazy">
                    </div>
                    <div class="search-details">
                        <h3 class="search-item-name">
                            <a href="product.php?id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                        <p class="search-item-price">$<?php echo number_format($product['price'], 2); ?></p>
                        <button class="btn btn-add" onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>