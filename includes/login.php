<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';
require_once 'functions.php';

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$result = loginUser($email, $password);
echo json_encode($result);

if ($result['success']) {
    header('Location: ../index.php');
    exit;
}
?>