<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../src/helpers/session_helper.php';

$apiKey = '0136e68e78a0433f8b5bdcec484af43c';
$searchQuery = isset($_GET['query']) ? urlencode($_GET['query']) : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : 'all genres';
$year = isset($_GET['year']) ? $_GET['year'] : 'all years';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$actor = isset($_GET['actor']) ? $_GET['actor'] : '';

$url = 'https://api.themoviedb.org/3/discover/movie?api_key=' . $apiKey;

if ($genre !== 'all genres') {
    if (is_numeric($genre)) {
        $url .= '&with_genres=' . urlencode($genre);
    }
}
if ($year !== 'all years') {
    if (strpos($year, '-') !== false) {
        list($startYear, $endYear) = explode('-', $year);
        $url .= '&primary_release_date.gte=' . $startYear . '-01-01';
        $url .= '&primary_release_date.lte=' . $endYear . '-12-31';
    } else {
        $url .= '&primary_release_year=' . $year;
    }
}
if (!empty($searchQuery)) {
    $url .= '&query=' . $searchQuery;
}
if (!empty($title)) {
    $url = 'https://api.themoviedb.org/3/search/movie?api_key=' . $apiKey . '&query=' . urlencode($title);
}

if (!empty($actor)) {
    // Search for actor by name
    $actorSearchUrl = 'https://api.themoviedb.org/3/search/person?api_key=' . $apiKey . '&query=' . urlencode($actor);
    $actorResponse = file_get_contents($actorSearchUrl);
    $actorData = json_decode($actorResponse, true);
    
    if (isset($actorData['results'][0]['id'])) {
        $actorId = $actorData['results'][0]['id'];
        // Fetch movies by actor ID
        $url = 'https://api.themoviedb.org/3/person/' . $actorId . '/movie_credits?api_key=' . $apiKey;
    }
}

$response = file_get_contents($url);
$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/explore.css">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<section class="section1">
    <header>
        <div class="navbar">
            <a href="#" class="logo">
                <img src="images/logo.png" alt="FilmQuest Logo" class="logo-img">
            </a>
            <span class="title-responsive"> Explore </span>
            <ul class="links">
                <li><a href="home.php">Home</a></li>
                <li><a href="home.php#about-section">About</a></li>
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php" class="active">Explore</a></li>
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
            <li><a href="home.php">Home</a></li>
            <li><a href="home.php#about-section">About</a></li>
            <li><a href="watchList.php">WatchList</a></li>
            <li><a href="explore.php" class="active">Explore</a></li>
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
    <section class="banner">
        <div class="banner-card">
            <a href="aboutMovie.html"><img src="images/scenetaste.jpg_large" class="banner-img" alt=""></a>
        </div>
        <div class="card-content">
            <div class="card-info">
                <div class="genre">
                    <span>History/Romance/Drama </span>
                </div>
                <div class="year">
                    <span> 2023 </span>
                </div>
                <div class="duration">
                    <span> 2h 16m </span>
                </div>
            </div>
            <h2 class="card-title">The Taste Of Things</h2>
        </div>
    </section>
    <section class="movies">
        <div class="filter-bar">
            <div class="filter-dropdowns">
                <form action="" method="get" id="filter-form">
                    <select name="genre" class="genre">
                        <option value="all genres">All genres</option>
                        <option value="28">Action</option>
                        <option value="12">Adventure</option>
                        <option value="16">Animation</option>
                        <option value="35">Comedy</option>
                        <option value="80">Crime</option>
                        <option value="99">Documentary</option>
                        <option value="18">Drama</option>
                        <option value="14">Fantasy</option>
                        <option value="10749">Romance</option>
                    </select>
                    <select name="year" class="year">
                        <option value="all years">All years</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2021">2021</option>
                        <option value="2020-2017">2020-2017</option>
                        <option value="2016-2010">2016-2010</option>
                        <option value="2009-2000">2009-2000</option>
                        <option value="2000-1990">2000-1990</option>
                    </select>
                    <input type="text" name="query" class="input" placeholder="Search...">
                    <input type="text" name="actor" class="input" placeholder="Actor...">
                    <button type="submit">Apply Filters</button>
                </form>
            </div>
        </div>
        <div class="movies-grid" id="movies-grid">
        <?php
            if (isset($data['cast']) && !empty($data['cast'])) {
                // If searching by actor, use 'cast' array
                foreach ($data['cast'] as $movie) {
                    $title = $movie['title'];
                    $posterPath = 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'];
                    $releaseYear = date('Y', strtotime($movie['release_date']));
                    $rating = $movie['vote_average'];

                    echo '<div class="movie-card">';
                    echo '<div class="card-head">';
                    echo '<img src="' . $posterPath . '" alt="' . $title . '" class="card-img">';
                    echo '<div class="card-overlay">';
                    echo '<div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>' . $rating . '</span></div>';
                    echo '<div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>';
                    echo '</div></div>';
                    echo '<div class="card-body">';
                    echo '<h3 class="card-title">' . $title . '</h3>';
                    echo '<div class="card-info"><span class="year">' . $releaseYear . '</span></div>';
                    echo '</div></div>';
                }
            } else if (isset($data['results']) && !empty($data['results'])) {
                foreach ($data['results'] as $movie) {
                    $title = $movie['title'];
                    $posterPath = 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'];
                    $releaseYear = date('Y', strtotime($movie['release_date']));
                    $rating = $movie['vote_average'];

                    echo '<div class="movie-card">';
                    echo '<div class="card-head">';
                    echo '<img src="' . $posterPath . '" alt="' . $title . '" class="card-img">';
                    echo '<div class="card-overlay">';
                    echo '<div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>' . $rating . '</span></div>';
                    echo '<div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>';
                    echo '</div></div>';
                    echo '<div class="card-body">';
                    echo '<h3 class="card-title">' . $title . '</h3>';
                    echo '<div class="card-info"><span class="year">' . $releaseYear . '</span></div>';
                    echo '</div></div>';
                }
            } else {
                echo '<p>No movies found</p>';
            }
        ?>
        </div>
        <button id="see-more-btn">See More</button>
    </section>
</section>
<script src="js/explore.js"></script>
</body>
</html>
