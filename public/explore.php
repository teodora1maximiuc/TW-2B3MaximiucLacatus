<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/explore.css">
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
                <li><a href="index.html">Home</a></li>
                <li><a href="index.html#about-section">About</a></li>
                <li><a href="watchList.html">WatchList</a></li>
                <li><a href="explore.html" class="active">Explore</a></li>
                <li><a href="help.html">Help</a></li>
                <li><a href="login.html">Login</a></li>
            </ul>
            <div class="toggle_btn">
                <i class="fa-solid fa-bars" style="color: #fff;"></i>
            </div>
        </div>
    </header>
    <div class="dropdown_menu">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="index.html#about-section">About</a></li>
            <li><a href="watchList.html">WatchList</a></li>
            <li><a href="explore.html" class="active">Explore</a></li>
            <li><a href="help.html">Help</a></li>
            <li><a href="login.html">Login</a></li>
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
                <select name="genre" class="genre">
                   <option value="all genres">All genres</option>
                    <option value="action">Action</option>
                    <option value="adventure">Adventure</option>
                    <option value="animal">Animal</option>
                    <option value="romance">Romance</option>
                    <option value="fantasy">Fantasy</option>
                    <option value="sci-fi">Sci-fi</option>
                </select>
                <select name="year" class="year">
                    <option value="all years">All the years</option>
                    <option value="2024">2024</option>
                    <option value="2020-2023">2020-2023</option>
                    <option value="2010-2019">2010-2019</option>
                    <option value="2000-2009">2000-2009</option>
                    <option value="1990-1999">1990-1999</option>
                </select>
            </div>
            <div class="search-bar">
                <input type="text" class="input" placeholder="Search...">
            </div>
            <div class="filter-radios">
                <input type="radio" name="grade" id="popular" checked>
                <label for="popular">Popular</label>

                <input type="radio" name="grade" id="newest">
                <label for="newest">Newest</label>
                <div class="checked-radio-bg"></div>
            </div>
        </div>
        <div class="movies-grid" id="movies-grid">
        <?php
$apiKey = '0136e68e78a0433f8b5bdcec484af43c';
$page = 1; // Start with page 1
$totalPages = 5; // Set the total number of pages you want to fetch (adjust as needed)
$moviesPerPage = 20; // Number of movies per page

for ($page = 1; $page <= $totalPages; $page++) {
    $url = 'https://api.themoviedb.org/3/discover/movie?api_key=' . $apiKey . '&page=' . $page;
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['results'])) {
        foreach ($data['results'] as $movie) {
            $title = $movie['title'];
            $posterPath = 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'];
            $genre = ''; // You can fetch genre information if needed
            $releaseYear = date('Y', strtotime($movie['release_date']));
            $rating = $movie['vote_average'];

            echo '<div class="movie-card">';
            echo '<div class="card-head">';
            echo '<img src="' . $posterPath . '" alt="' . $title . '" class="card-img">';
            echo '<div class="card-overlay">';
            echo '<div class="bookmark"><i class="fa-regular fa-bookmark" style="color: #fff;"></i></div>';
            echo '<div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>' . $rating . '</span></div>';
            echo '<div class="addWatchList"><i class="fa-solid fa-circle-plus" style="color: #fff;"></i></div>';
            echo '</div></div>';
            echo '<div class="card-body">';
            echo '<h3 class="card-title">' . $title . '</h3>';
            echo '<div class="card-info"><span class="genre">' . $genre . ' - </span><span class="year">' . $releaseYear . '</span></div>';
            echo '</div></div>';
        }
    } else {
        echo '<p>No movies found</p>';
    }

    // Delay before making the next API request to avoid rate limiting (optional)
    usleep(500000); // Sleep for 0.5 seconds
}
?>
        </div>
    </section>
    <section class="statistic">
        <div class="statistics">
            <h2>Statistics</h2>
        </div>
        <div class="stat-images">
            <img src="images/statistics-films.png" alt="" class="film-stat">
            <img src="images/actors-statistics.jpg" alt="" class="actor-stat">
        </div>
    </section>
</section>
<script>
const toggleBtn = document.querySelector('.toggle_btn');
const toggleBtnIcon = document.querySelector('.toggle_btn i');
const dropDownMenu = document.querySelector('.dropdown_menu');

toggleBtn.onclick = function() {
    dropDownMenu.classList.toggle('open');
    const isOpen = dropDownMenu.classList.contains('open');
    toggleBtnIcon.classList = isOpen
        ? 'fa-solid fa-xmark'
        : 'fa-solid fa-bars';
};
</script>
</body>
</html>
