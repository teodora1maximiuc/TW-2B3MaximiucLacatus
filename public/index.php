<?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';

    switch ($page) {
        case 'home':
            include 'home.php';
            break;
        case 'about':
            header('Location: home.php?page=home#about-section');
            exit;
        case 'watchlist':
            include 'watchList.php';
            break;
        case 'explore':
            include 'explore.php';
            break;
        case 'help':
            include 'help.php';
            break;
        case 'login':
            include 'login.php';
            break;
        default:
            include '404.html';
            break;
    }
?>
