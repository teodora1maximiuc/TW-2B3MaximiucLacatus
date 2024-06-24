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
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="icon" href="images/tab_logo.png" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <section class = "section1">
        <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="images/logo.png" alt="FilmQuest Logo" class="logo-img">
            </a>
            <ul class="links">
                <li><a href="home.php?page=home" class="active">Home</a></li>
                <li><a href="index.php?page=about">About</a></li>
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="statistics.php">Statistics</a></li>
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
        <div class="dropdown_menu">
            <ul>
                <li><a href="home.php" class="active">Home</a></li>
                <li><a href="#about-section">About</a></li>
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="statistics.php">Statistics</a></li>
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
        </div>
        <div class="title_search">
            <h2 id="text">FilmQuest</h2>
            <div class="search">
                <input type="text" class="input" id="searchInput" placeholder="Search...">
                <button onclick="performSearch()" class="search-btn">Search</button>
            </div>
            <div class="category">
                <div class="select">
                    <span class="selected">Search by movie name</span>
                    <div class="caret"></div>
                </div>
                <ul class="categories">
                    <li class="active" value="title">Search by movie name</li>
                    <li value="year">Search by year</li>
                    <li value="actor">Search by actor</li>
                </ul>
            </div>
            <div id="about-section" class="about-section">
                <h2>About</h2>
                <p>FilmQuest is the ultimate destination for movie enthusiasts. Our platform offers a vast collection of films from various genres, eras, and cultures, catering to all kinds of movie preferences.</p>
                <p>Dive into our extensive movie library to find your next favorite film. Whether you're into action, comedy, drama, or something else entirely, FilmQuest has you covered.</p>
                <p>Easily search for movies by title, year, genre, or actor to discover new favorites or revisit classic gems. Our user-friendly interface makes browsing a breeze.</p>
                <p>Stay informed about the latest movie releases, industry news, and exclusive content through FilmQuest's news and updates section. Never miss out on what's happening in the world of cinema.</p>
                <p>Ready to embark on your movie journey? Sign up for FilmQuest today and start exploring a world of cinematic wonders.</p>
            </div>
        </div>
        <section class="container">
            <div class="bucket">
                <img src="images/popcorn-buckets.png" class="bucket-image" alt="">
            </div>
            <img src="images/one_popcorn.png" class="popcorn" id="popcorn" alt="">
        </section>
        <script>
            function performSearch() {
                const selectedCategory = document.querySelector('.selected').innerText.toLowerCase();
                const searchInput = document.getElementById('searchInput').value.trim();
                let searchUrl = `explore.php?`;

                if (selectedCategory.includes('movie name')) {
                    searchUrl += `title=${encodeURIComponent(searchInput)}`;
                } else if (selectedCategory.includes('year')) {
                    searchUrl += `year=${encodeURIComponent(searchInput)}`;
                } else if (selectedCategory.includes('actor')) {
                    searchUrl += `actor=${encodeURIComponent(searchInput)}`;
                } else {
                    searchUrl += `title=${encodeURIComponent(searchInput)}`;
                }

                window.location.href = searchUrl;
            }
            const popcorn = document.getElementById('popcorn');

            window.addEventListener('scroll', () => {
                const scrollTop = window.scrollY;
                const documentHeight = document.body.clientHeight;
                const maxScroll = documentHeight - window.innerHeight;
                const scrollPercentage = (scrollTop / maxScroll) * 100;

                const maxRotation = 300; 
                const rotation = (maxRotation * scrollPercentage) / 100;

                popcorn.style.transform = `rotate(${rotation}deg)`;
            });

            const toggleBtn = document.querySelector('.toggle_btn');
            const toggleBtnIcon = document.querySelector('.toggle_btn i');
            const dropDownMenu = document.querySelector('.dropdown_menu');

            toggleBtn.onclick = function() {
                dropDownMenu.classList.toggle('open');
                const isOpen = dropDownMenu.classList.contains('open');
                toggleBtnIcon.classList = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
            };

            const categories = document.querySelectorAll('.category');
            categories.forEach(category => {
                const select = category.querySelector('.select');
                const caret = category.querySelector('.caret');
                const categoriesList = category.querySelector('.categories');
                const options = category.querySelectorAll('.categories li');
                const selected = category.querySelector('.selected');

                select.addEventListener('click', () => {
                    select.classList.toggle('select-clicked');
                    caret.classList.toggle('caret-rotate');
                    categoriesList.classList.toggle('categories-open');
                });

                options.forEach(option => {
                    option.addEventListener('click', () => {
                        selected.innerText = option.innerText;
                        select.classList.remove('select-clicked');
                        caret.classList.remove('caret-rotate');
                        categoriesList.classList.remove('categories-open');
                        options.forEach(opt => {
                            opt.classList.remove('active');
                        });
                        option.classList.add('active');
                    });
                });
            });
        </script>

        </section>
        <section class = "end">
        </section>
    </body>
</html>