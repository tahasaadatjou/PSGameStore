<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['productId']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$productId = (int)$data['productId'];
$quantity = (int)$data['quantity'];

if ($quantity < 1) {
    echo json_encode(['success' => false, 'message' => 'Quantity must be at least 1']);
    exit;
}

if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId]['quantity'] = $quantity;
    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode(['success' => true, 'cartCount' => $cartCount]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not in cart']);
}
?>