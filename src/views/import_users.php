<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include_once __DIR__ . '/../../src/helpers/session_helper.php';
    include_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../../src/models/User.php';

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file']['tmp_name'];
        $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

        try {
            $pdo->beginTransaction();

            if ($extension == 'csv') {
                $handle = fopen($file, "r");
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $first_name = $data[0];
                    $last_name = $data[1];
                    $username = $data[2];
                    $email = $data[3];
                    $pwd = password_hash($data[4], PASSWORD_DEFAULT);
                    $is_admin = $data[5];

                    /*verificam daca exista deja utilizatorul in baza de date*/
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                    $stmt->execute([$username, $email]);
                    $existingUser = $stmt->fetch();
                    /*se adauga userul daca nu exista deja */
                    if (!$existingUser) {
                        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, pwd, is_admin) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$first_name, $last_name, $username, $email, $pwd, $is_admin]);
                    }
                }
                fclose($handle);
            } elseif ($extension == 'json') {
                $jsonData = file_get_contents($file);
                $users = json_decode($jsonData, true);
                foreach ($users as $user) {
                    $pwd = password_hash($user['password'], PASSWORD_DEFAULT);

                    /*verificam daca exista deja utilizatorul in baza de date */
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                    $stmt->execute([$user['username'], $user['email']]);
                    $existingUser = $stmt->fetch();
                    /*se adauga userul daca nu exista deja */
                    if (!$existingUser) {
                        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, username, email, pwd, is_admin) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$user['first_name'], $user['last_name'], $user['username'], $user['email'], $pwd, $user['is_admin']]);
                    }
                }
            }

            $pdo->commit();
            echo json_encode(['success' => true]);
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit();
        }
    }

    echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>
