<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['productId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$productId = (int)$data['productId'];

if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
    $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
    echo json_encode(['success' => true, 'cartCount' => $cartCount]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not in cart']);
}
?>