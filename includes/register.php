<?php
session_start();
header('Content-Type: application/json');
require_once 'config.php';
require_once 'functions.php';

$data = json_decode(file_get_contents('php://input'), true);
$username = filter_var($data['username'] ?? '', FILTER_SANITIZE_STRING);
$email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
$password = $data['password'] ?? '';
$confirmPassword = $data['confirmPassword'] ?? '';

if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

$result = registerUser($username, $email, $password);
echo json_encode($result);

if ($result['success']) {
    loginUser($email, $password);
    header('Location: ../index.php');
    exit;
}
?>