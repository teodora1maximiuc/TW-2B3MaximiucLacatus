<?php
include_once __DIR__ . '/../../src/helpers/session_helper.php';
include_once __DIR__ . '/../../config/config.php';

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        redirect(BASE_URL . 'public/user_management.php');
        exit();
    } else {
        echo "Error: Unable to remove user.";
    }
} else {
    echo "Error: Invalid user ID.";
}
?>
