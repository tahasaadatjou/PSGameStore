<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$base_url = $protocol . "://$_SERVER[HTTP_HOST]" . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta_description ?? 'PlayStation Game Store'; ?>">
    <title><?php echo $page_title ?? 'PlayStation Game Store'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
    <link rel="icon" href="<?php echo $base_url; ?>assets/images/favicon.ico">
</head>
<body>
    <header class="modern-header">
        <div class="header-container">
            <a href="<?php echo $base_url; ?>" class="logo">
                <img src="<?php echo $base_url; ?>assets/images/ps-logo.png" alt="PlayStation Store" width="140">
            </a>
            <nav class="main-nav">
                <ul>
                    <li><a href="<?php echo $base_url; ?>">Home</a></li>
                    <li><a href="<?php echo $base_url; ?>games.php">Games</a></li>
                    <li><a href="<?php echo $base_url; ?>deals.php">Deals</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <div class="search-container">
                    <form action="<?php echo $base_url; ?>search.php" method="get">
                        <input type="search" id="search-input" name="q" placeholder="Search games..." aria-label="Search games" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button class="search-btn" type="submit" aria-label="Search">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <a href="<?php echo $base_url; ?>cart.php" class="cart-icon" aria-label="Cart">
                    <svg viewBox="0 0 24 24" width="24" height="24">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                    </svg>
                    <span class="cart-count"><?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?></span>
                </a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo $base_url; ?>user-panel.php" class="user-icon" aria-label="User Panel">
                        <svg viewBox="0 0 24 24" width="24" height="24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span><?php echo htmlspecialchars(getCurrentUser()['username']); ?></span>
                    </a>
                    <a href="<?php echo $base_url; ?>includes/logout.php" class="btn btn-gradient">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>login-page.php" class="btn btn-gradient">Login</a>
                    <a href="<?php echo $base_url; ?>register-page.php" class="btn btn-gradient">Register</a>
                <?php endif; ?>
                <button class="menu-toggle" aria-label="Toggle Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>
    <div class="main-wrapper">