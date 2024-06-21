<?php
session_start();
include_once __DIR__ . '/../src/helpers/session_helper.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/watchList.css">
    <link href="https://fonts.googleapis.com/css?family=Lucky+Bones&display=swap" rel="stylesheet">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">
</head>
<body>
    <section class="section1">
        <header>
            <div class="navbar">
                <a href="#" class="logo">
                    <img src="images/logo.png" alt="FilmQuest Logo" class="logo-img">
                </a>
                <ul class="links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="home.php#about-section">About</a></li>
                    <li><a href="watchList.php" class="active">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
                    <li><a href="help.php">Help</a></li>
                    <?php if(isAdmin()) : ?>
                        <li><a href="user_management.php">User Management</a></li>
                    <?php endif; ?>
                    <?php if(!isset($_SESSION['user_id'])) : ?> 
                    <li><a href="login.php">Login</a></li>
                    <?php else : ?>
                        <li><a href="../src/controllers/Users.php?q=logout">Logout</a></li>
                    <?php endif; ?>
                </ul>
                <div class="toggle_btn">
                    <i class="fa-solid fa-bars" style="color: #fff;"></i>
                </div>
            </div>
        </header>
        <div class="not-logged-in">
            <div class="not-logged-in-content">
                <h2>You are not logged in.</h2>
                <p>To see your Watch Lists, you need to log in first.</p>
                <p><a href="login.php">Go to Login page.</a></p>
            </div>
        </div>
        <div class="watch-list-nav">
            <div class="list-bar">
                <ul>
                    <li><a href="#" class="active">All</a></li>
                    <li><a href="#">Watching</a></li>
                    <li><a href="#">Completed</a></li>
                    <li><a href="#">Dropped</a></li>
                    <li><a href="#">Plan to Watch</a></li>
                    <li><h1 class="page-title">Watch List</h1></li>
                </ul>
            </div>
        </div>
        <div class="dropdown_menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="home.php#about-section">About</a></li>
                <li><a href="watchList.php" class="active">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="watchList.php">Help</a></li>
                <?php if(isAdmin()) : ?>
                    <li><a href="user_management.php">User Management</a></li>
                <?php endif; ?>
                <?php if(!isset($_SESSION['user_id'])) : ?> 
                    <li><a href="login.php">Login</a></li>
                <?php else : ?>
                    <li><a href="../src/controllers/Users.php?q=logout">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const watchListLinks = document.querySelectorAll('.list-bar ul li a');
            const loginMessage = document.getElementById('login-message');
            function handleWatchListClick(event) {
                event.preventDefault();
                watchListLinks.forEach(link => {
                    link.classList.remove('active');
                });
                this.classList.add('active');
            }
            watchListLinks.forEach(link => {
                link.addEventListener('click', handleWatchListClick);
            });
        });
    
        const toggleBtn = document.querySelector('.toggle_btn')
        const toggleBtnIcon = document.querySelector('.toggle_btn i')
        const dropDownMenu = document.querySelector('.dropdown_menu')
    
        toggleBtn.onclick = function() {
            dropDownMenu.classList.toggle('open')
            const isOpen = dropDownMenu.classList.contains('open')
            toggleBtnIcon.classList = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
        }
    </script>
    
</body>
</html>