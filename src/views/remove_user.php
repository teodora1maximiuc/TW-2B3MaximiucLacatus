<?php
include_once __DIR__ . '/../../src/helpers/session_helper.php';
include_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (isset($_POST['id'])) {
    $userId = intval($_POST['id']);
    
    $pdo->beginTransaction();

    try {
        /*stergem intai randurile din watchlist ale utilizatorului -- avem foreign key */
        $queryWatchlist = "DELETE FROM watchlist WHERE user_id = :userId";
        $stmtWatchlist = $pdo->prepare($queryWatchlist);
        $stmtWatchlist->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmtWatchlist->execute();

        $queryUsers = "DELETE FROM users WHERE id = :id";
        $stmtUsers = $pdo->prepare($queryUsers);
        $stmtUsers->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmtUsers->execute();

        $pdo->commit();

        $response['success'] = true;
        $response['message'] = 'User removed successfully.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = 'Unable to remove user: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid user ID.';
}

echo json_encode($response);
?>
