<?php
session_start();
include_once __DIR__ . '/../src/helpers/session_helper.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/help.css">
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
                    <li><a href="watchList.php">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="help.php" class="active">Help</a></li>
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
        <div class="dropdown_menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="home.php#about-section">About</a></li>
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php" >Explore</a></li>
                <li><a href="statistics.php">Statistics</a></li>
                <li><a href="help.php" class="active">Help</a></li>
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
        <div class="title">
            <h1>Help Center</h1>
        </div>
        <div class="help-cont">
            <div class="help-info">
                <h2>Finding Movies</h2>
                <p>If you're looking for a specific movie, use our search feature located on the <a href="home.php">Home</a> page. Simply enter the movie title or keywords related to the movie, and we'll provide you with relevant results.</p>
                <p>Or, for a more precise result, use our Explore feature which allows you to customize your search in order to get better results.</p>
            </div>
            <div class="help-info">
                <h2>Managing Watch List</h2>
                <p>To manage your watch lists, use the <a href="watchList.php">WatchList</a> button on the navigation menu. From there, you can add movies to either 'Watching', 'Completed', 'Dropped' or 'Plan to Watch' categories, remove, or mark movies as watched.</p>
            </div>
            <div class="help-info">
                <h2>Creating an account</h2>
                <p>In order to use the WatchList feature you will need to create an account.</p>
                <p>You can easily do this by visiting on the <a href="login.php">Login</a> page, where you can be redirected to the Sign in page if you don't already have an account.</p>
            </div>
            <div class="help-info">
                <h2>More about us</h2>
                <p>You can find more information about the mission of this web site in the <a href="home.php#about-section">About</a> section.</p>
            </div>
        </div>
        <div class="dropdown_menu">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="home.php#about-section">About</a></li>
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="help.php" class="active">Help</a></li>
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
