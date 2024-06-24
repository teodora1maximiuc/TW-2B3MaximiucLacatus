<?php
include_once __DIR__ . '/../../src/helpers/session_helper.php';
include_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    if ($extension == 'csv') {
        $handle = fopen($file, "r");
        while (($data = fgetcsv($handle)) !== FALSE) {
            $email = $data[3];
            $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
            $stmt->execute([$email]);
        }
        fclose($handle);
    } elseif ($extension == 'json') {
        $jsonData = file_get_contents($file);
        $users = json_decode($jsonData, true);
        foreach ($users as $user) {
            $stmt = $pdo->prepare("DELETE FROM users WHERE email = ?");
            $stmt->execute([$user['email']]);
        }
    }

    echo json_encode(['success' => true]);
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
