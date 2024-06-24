<?php
include_once __DIR__ . '/../../src/helpers/session_helper.php';
include_once __DIR__ . '/../../config/config.php';

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    if ($pdo) {
        $query = "SELECT is_admin FROM users WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {

            $newPermission = $user['is_admin'] ? 0 : 1;
            $query = "UPDATE users SET is_admin = :is_admin WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':is_admin', $newPermission, PDO::PARAM_INT);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                /*redirect(BASE_URL . 'public/user_management.php');
                exit();*/
                $response['success'] = true;
                $response['newPermission'] = $newPermission;
                $response['message'] = 'Permissions changed successfully';
            } else {
                //echo "Error: Unable to change permissions.";
                $response['message'] = 'Unable to change permissions';
            }
        } else {
            //echo "Error: User not found.";
            $response['message'] = 'User not found';
        }
    } else {
        //echo "Error: Database connection not available.";
        $response['message'] = 'Database connection not available';
    }
} else {
    //echo "Error: Invalid user ID.";
    $response['message'] = 'Invalid user ID';
}

echo json_encode($response);
?>
