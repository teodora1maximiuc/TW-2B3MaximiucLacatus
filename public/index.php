<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'home':
        include 'src\views\html\index.html';
        break;
    case 'about':
        header('Location: index.php?page=home#about-section');
        exit;
    case 'watchlist':
        include 'watchList.html';
        break;
    case 'explore':
        include 'explore.html';
        break;
    case 'help':
        include 'help.html';
        break;
    case 'login':
        include 'login.html';
        break;
    default:
        include '404.html'; // Custom 404 page
        break;
}
?>
