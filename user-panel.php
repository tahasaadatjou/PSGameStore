<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login-page.php');
    exit;
}

$page_title = "User Panel - PlayStation Game Store";
$meta_description = "Manage your PlayStation Game Store account.";
include 'includes/header.php';

$user = getCurrentUser();
?>

<div class="user-panel-container">
    <h2 class="section-title">Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
    <div class="panel-content">
        <div class="user-info">
            <h3>Account Details</h3>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <a href="includes/logout.php" class="btn btn-gradient">Logout</a>
        </div>
        <div class="user-orders">
            <h3>Your Orders</h3>
            <p>No orders yet. Start shopping now!</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>