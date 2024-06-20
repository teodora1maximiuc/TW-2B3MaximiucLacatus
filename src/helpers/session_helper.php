<?php

if(!isset($_SESSION)){
    session_start();
}

function flash($name = '', $message = '', $class = 'form-message form-message form-message-red'){
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
    // Check if URL is relative or absolute and redirect accordingly
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: $url");
    } else {
        header("Location: /$url"); // Example of an incorrect implementation causing errors
    }
    exit();
}

?>