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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'], $_POST['category'])) {
    $userId = $_SESSION['user_id'];
    $movieId = htmlspecialchars($_POST['movie_id']);
    $category = htmlspecialchars($_POST['category']);

    $stmt = $pdo->prepare('SELECT * FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id');
    $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
    $existingWatchlist = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingWatchlist) {
        $message = "Movie is already in your watchlist under category: {$existingWatchlist['category']}.";
        echo json_encode(['status' => 'error', 'message' => $message]);
        exit();
    }

    // Add the movie to the watchlist
    $stmt = $pdo->prepare('INSERT INTO watchlist (user_id, movie_id, category) VALUES (:user_id, :movie_id, :category)');
    $result = $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId, 'category' => $category]);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Movie added to your watchlist successfully.']);
        exit();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Failed to add movie to your watchlist. Please try again.']);
        exit();
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Bad Request']);
    exit();
}
?>
