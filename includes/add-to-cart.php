<?php
session_start();
header('Content-Type: application/json');

require_once 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['productId'])) {
    echo json_encode(['success' => false, 'message' => 'Product ID missing']);
    exit;
}

$productId = (int)$data['productId'];

try {
    $stmt = $GLOBALS['pdo']->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if (!$product) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];
    }

    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode([
        'success' => true,
        'cartCount' => $cartCount,
        'message' => 'Product added to cart'
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>