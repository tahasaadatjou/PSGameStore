<?php
$db_config = [
    'host' => 'localhost',
    'dbname' => '',
    'username' => '',
    'password' => '',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]
];

try {
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4";
    $GLOBALS['pdo'] = new PDO($dsn, $db_config['username'], $db_config['password'], $db_config['options']);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("We're experiencing technical difficulties. Please try again later.");
}

date_default_timezone_set('UTC');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = null;
}
?>
