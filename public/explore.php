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
                <form action="" method="get" id="filter-form">
                    <select name="genre" class="genre">
                        <option value="all genres">All genres</option>
                        <option value="28">Action</option>
                        <option value="12">Adventure</option>
                        <option value="16">Animation</option>
                        <option value="35">Comedy</option>
                        <option value="80">Crime</option>
                        <option value="80">Documentary</option>
                        <option value="80">Drama</option>
                        <option value="80">Fantasy</option>
                        <option value="80">Romance</option>
                    </select>
                    <select name="year" class="year">
                        <option value="all years">All years</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                        <option value="2022">2021</option>
                        <option value="2022">2020</option>
                        <option value="2022">2019</option>
                        <option value="2022">2018</option>
                        <option value="2022">2017</option>
                    </select>
                    <button type="submit">Apply Filters</button>
                </form>
            </div>
        </div>
        <div class="movies-grid" id="movies-grid">
            <?php
            $apiKey = '0136e68e78a0433f8b5bdcec484af43c';
            $genre = isset($_GET['genre']) ? $_GET['genre'] : 'all genres';
            $year = isset($_GET['year']) ? $_GET['year'] : 'all years';
            $grade = isset($_GET['grade']) ? $_GET['grade'] : 'popular';
            $url = 'https://api.themoviedb.org/3/discover/movie?api_key=' . $apiKey;

            if ($genre !== 'all genres') {
                $url .= '&with_genres=' . urlencode($genre);
            }

            if ($year !== 'all years') {
                $url .= '&primary_release_year=' . $year;
            }

            if ($grade === 'newest') {
                $url .= '&sort_by=release_date.desc';
            } else {
                $url .= '&sort_by=popularity.desc';
            }
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            if (isset($data['results'])) {
                foreach ($data['results'] as $movie) {
                    $title = $movie['title'];
                    $posterPath = 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'];
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
            ?>
        </div>
        <button id="see-more-btn">See More</button>
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
$(document).ready(function() {
    let currentPage = 1;
    const totalPages = 10;

    function fetchMovies(page) {
        const urlParams = new URLSearchParams(window.location.search);
        const genre = urlParams.get('genre'); 
        const year = urlParams.get('year'); 

        let apiUrl = 'https://api.themoviedb.org/3/discover/movie?api_key=<?= $apiKey ?>&page=' + page;

        if (genre && genre !== 'all genres') {
            apiUrl += '&with_genres=' + encodeURIComponent(genre);
        }
        if (year && year !== 'all years') {
            if (year.includes('-')) {
                const [startYear, endYear] = year.split('-');
                apiUrl += '&primary_release_date.gte=' + startYear + '-01-01';
                apiUrl += '&primary_release_date.lte=' + endYear + '-12-31';
            } else {
                apiUrl += '&primary_release_year=' + year;
            }
        }

        $.get(apiUrl, function(data) {
            let moviesHtml = '';
            if (data.results.length > 0) {
                data.results.forEach(function(movie) {
                    const title = movie.title;
                    const posterPath = 'https://image.tmdb.org/t/p/w500/' + movie.poster_path;
                    const releaseYear = new Date(movie.release_date).getFullYear();
                    const rating = movie.vote_average;

                    moviesHtml += '<div class="movie-card">';
                    moviesHtml += '<div class="card-head">';
                    moviesHtml += '<img src="' + posterPath + '" alt="' + title + '" class="card-img">';
                    moviesHtml += '<div class="card-overlay">';
                    moviesHtml += '<div class="bookmark"><i class="fa-regular fa-bookmark" style="color: #fff;"></i></div>';
                    moviesHtml += '<div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>' + rating + '</span></div>';
                    moviesHtml += '<div class="addWatchList"><i class="fa-solid fa-circle-plus" style="color: #fff;"></i></div>';
                    moviesHtml += '</div></div>';
                    moviesHtml += '<div class="card-body">';
                    moviesHtml += '<h3 class="card-title">' + title + '</h3>';
                    moviesHtml += '<div class="card-info"><span class="genre">' + genre + ' - </span><span class="year">' + releaseYear + '</span></div>';
                    moviesHtml += '</div></div>';
                });
                $('.movies-grid').append(moviesHtml);
            } else {
                $('.movies-grid').append('<p>No more movies found</p>');
            }
        });
    }
    $('#apply-filters-btn').on('click', function() {
        $('.movies-grid').html('');
        fetchMovies(1);
        currentPage = 1; 
    });
    $('#see-more-btn').on('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            fetchMovies(currentPage); 
        } else {
            alert('No more pages to load');
        }
    });
});
</script>
</body>
</html>
