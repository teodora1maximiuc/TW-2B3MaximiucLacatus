<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../helpers/session_helper.php';
require_once __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit();
}

if (!isset($_POST['movie_id'], $_POST['new_category'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing parameters']);
    exit();
}

$user_id = $_SESSION['user_id'];
$movie_id = $_POST['movie_id'];
$new_category = $_POST['new_category'];

global $pdo;
try {
    $stmt = $pdo->prepare("UPDATE watchlist SET category = :new_category WHERE user_id = :user_id AND movie_id = :movie_id");
    $stmt->bindParam(':new_category', $new_category, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);
    
    $executeResult = $stmt->execute();
    
    if ($executeResult && $stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Movie not found in watchlist or you do not have permission to update category.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
