<?php

if(!isset($_SESSION)){
    session_start();
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    require_once '../../config/config.php';
    $stmt = $pdo->prepare('SELECT user_id FROM user_tokens WHERE token = :token');
    $stmt->execute(['token' => $token]);
    $userToken = $stmt->fetch(PDO::FETCH_OBJ);

    if ($userToken) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $userToken->user_id]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if ($user) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['email'] = $user->email;
            $_SESSION['is_admin'] = $user->is_admin;
        }
    }
}


function flash($name = '', $message = '', $class = 'form-message form-message-red'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        }else if(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : $class;
            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}

function redirect($url) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: $url");
    } else {
        header("Location: /$url");
    }
    exit();
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

?>