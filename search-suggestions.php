<?php
header('Content-Type: application/json');
require_once 'includes/config.php';

$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($searchQuery)) {
    try {
        $stmt = $GLOBALS['pdo']->prepare("SELECT id, name FROM products WHERE name LIKE :query OR description LIKE :query ORDER BY name LIMIT 5");
        $stmt->execute([':query' => "%$searchQuery%"]);
        $results = $stmt->fetchAll();
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Search error']);
    }
} else {
    echo json_encode([]);
}
?>