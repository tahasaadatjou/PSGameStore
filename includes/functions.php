<?php
function getProductById($id) {
    try {
        $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching product: " . $e->getMessage());
        return false;
    }
}

function getCartTotal() {
    if (empty($_SESSION['cart'])) return 0;
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function getReviewCount($productId) {
    try {
        $stmt = $GLOBALS['pdo']->prepare("SELECT COUNT(*) FROM reviews WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Error fetching review count: " . $e->getMessage());
        return 0;
    }
}

function calculateDiscountPrice($price, $discount) {
    return number_format($price * (1 - $discount / 100), 2);
}

function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

function getCurrentUser() {
    return isLoggedIn() ? $_SESSION['user'] : null;
}

function registerUser($username, $email, $password) {
    try {
        $stmt = $GLOBALS['pdo']->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $GLOBALS['pdo']->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);
        return ['success' => true, 'message' => 'Registration successful'];
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

function loginUser($email, $password) {
    try {
        $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email']
            ];
            return ['success' => true, 'message' => 'Login successful'];
        }
        return ['success' => false, 'message' => 'Invalid email or password'];
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

function logoutUser() {
    unset($_SESSION['user']);
    session_destroy();
}
?>