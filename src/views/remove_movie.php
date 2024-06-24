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

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['movie_id'])) {
        $userId = $_SESSION['user_id'];
        $movieId = htmlspecialchars($_POST['movie_id']);

        $stmt = $pdo->prepare('SELECT * FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id');
        $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);
        $existingWatchlist = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingWatchlist) {
            $message = "Movie not found in your watchlist.";
            echo json_encode(['status' => 'error', 'message' => $message]);
            exit();
        }

        $stmt = $pdo->prepare('DELETE FROM watchlist WHERE user_id = :user_id AND movie_id = :movie_id');
        $result = $stmt->execute(['user_id' => $userId, 'movie_id' => $movieId]);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'Movie removed from your watchlist successfully.']);
            exit();
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to remove movie from your watchlist. Please try again.']);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Bad Request']);
        exit();
    }
?>
