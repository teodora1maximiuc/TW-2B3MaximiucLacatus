<?php
    include_once __DIR__ . '/../../src/helpers/session_helper.php';
    include_once __DIR__ . '/../../config/config.php';

    header('Content-Type: application/json');

    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['id'])) {
        $userId = intval($_POST['id']);

        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'User removed successfully.';
        } else {
            $response['message'] = 'Unable to remove user.';
        }
    } else {
        $response['message'] = 'Invalid user ID.';
    }

    echo json_encode($response);
?>
