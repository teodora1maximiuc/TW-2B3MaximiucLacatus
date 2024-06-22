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
                        <option value="all genres" <?php echo ($genre === 'all genres') ? 'selected' : ''; ?>>All genres</option>
                        <option value="28" <?php echo ($genre === '28') ? 'selected' : ''; ?>>Action</option>
                        <option value="12" <?php echo ($genre === '12') ? 'selected' : ''; ?>>Adventure</option>
                        <option value="16" <?php echo ($genre === '16') ? 'selected' : ''; ?>>Animation</option>
                        <option value="35" <?php echo ($genre === '35') ? 'selected' : ''; ?>>Comedy</option>
                        <option value="80" <?php echo ($genre === '80') ? 'selected' : ''; ?>>Crime</option>
                        <option value="99" <?php echo ($genre === '99') ? 'selected' : ''; ?>>Documentary</option>
                        <option value="18" <?php echo ($genre === '18') ? 'selected' : ''; ?>>Drama</option>
                        <option value="14" <?php echo ($genre === '14') ? 'selected' : ''; ?>>Fantasy</option>
                        <option value="10749" <?php echo ($genre === '10749') ? 'selected' : ''; ?>>Romance</option>
                    </select>
                    <select name="year" class="year">
                        <option value="all years" <?php echo ($year === 'all years') ? 'selected' : ''; ?>>All years</option>
                        <option value="2024" <?php echo ($year === '2024') ? 'selected' : ''; ?>>2024</option>
                        <option value="2023" <?php echo ($year === '2023') ? 'selected' : ''; ?>>2023</option>
                        <option value="2022" <?php echo ($year === '2022') ? 'selected' : ''; ?>>2022</option>
                        <option value="2021" <?php echo ($year === '2021') ? 'selected' : ''; ?>>2021</option>
                        <option value="2020-2017" <?php echo ($year === '2020-2017') ? 'selected' : ''; ?>>2020-2017</option>
                        <option value="2016-2010" <?php echo ($year === '2016-2010') ? 'selected' : ''; ?>>2016-2010</option>
                        <option value="2009-2000" <?php echo ($year === '2009-2000') ? 'selected' : ''; ?>>2009-2000</option>
                        <option value="2000-1990" <?php echo ($year === '2000-1990') ? 'selected' : ''; ?>>2000-1990</option>
                    </select>
                    <button type="submit" id="apply-filters-btn">Apply Filters</button>
                    </form>
                </div>
                <div class="search">
                    <input type="text" class="input" id="searchInput" placeholder="Search...">
                    <button onclick="performSearch()" class="search-btn">Search</button>
                </div>
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
<script>$(document).ready(function() {
    let currentPage = 1;
    let totalPages = 10;

    function performSearch() {
        const searchInput = document.getElementById('searchInput').value.trim();
        const searchUrl = 'explore.php?title=' + encodeURIComponent(searchInput);
        window.location.href = searchUrl;
    }

    document.querySelector('.search-btn').addEventListener('click', performSearch);

    function fetchMovies(page, clear = false) {
        const urlParams = new URLSearchParams(window.location.search);
        const genre = urlParams.get('genre');
        const year = urlParams.get('year');
        const title = urlParams.get('title');
        if (title) {
            document.getElementById('searchInput').value = title || '';
        }
        let apiUrl = '';

        if (title) {
            apiUrl = 'https://api.themoviedb.org/3/search/movie?api_key=<?= $apiKey ?>&query=' + encodeURIComponent(title) + '&page=' + page;
        } else {
            apiUrl = 'https://api.themoviedb.org/3/discover/movie?api_key=<?= $apiKey ?>';
            if (genre && genre !== 'all genres') {
                apiUrl += '&with_genres=' + encodeURIComponent(genre);
            }
            if (year && year !== 'all years') {
                console.log('Year:', year);
                if (year.includes('-')) {
                    const [startYear, endYear] = year.split('-');
                    apiUrl += '&primary_release_date.gte=' + endYear + '-01-01';
                    apiUrl += '&primary_release_date.lte=' + startYear + '-12-31';
                    console.log('url:', apiUrl);
                } else {
                    apiUrl += '&primary_release_year=' + year;
                }
            }
            apiUrl += '&page=' + page; 
            console.log('url:', apiUrl);
        }
        $.get(apiUrl, function(data) {
            if (clear) {
                $('.movies-grid').html(''); // Clear previous results
            }

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
                    moviesHtml += '<div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>' + rating + '</span></div>';
                    moviesHtml += '<div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>';
                    moviesHtml += '</div></div>';
                    moviesHtml += '<div class="card-body">';
                    moviesHtml += '<h3 class="card-title">' + title + '</h3>';
                    moviesHtml += '<div class="card-info"><span class="year">' + releaseYear + '</span></div>';
                    moviesHtml += '</div></div>';
                });
                $('.movies-grid').append(moviesHtml);
            } else {
                if (page === 1) {
                    $('.movies-grid').html('<p>No movies found</p>');
                } else {
                    $('.movies-grid').append('<p>No more movies found</p>');
                }
            }
            totalPages = data.total_pages || totalPages;
            if (currentPage >= totalPages) {
                $('#see-more-btn').hide();
            } else {
                $('#see-more-btn').show();
            }
        });
    }

    function applyFilters() {
        const genre = document.querySelector('select[name="genre"]').value;
        const year = document.querySelector('select[name="year"]').value;

        const urlParams = new URLSearchParams();
        if (genre && genre !== 'all genres') {
            urlParams.set('genre', genre);
        }
        if (year && year !== 'all years') {
            urlParams.set('year', year);
        }

        const newUrl = window.location.pathname + '?' + urlParams.toString();
        window.location.href = newUrl;
    }

    $('#apply-filters-btn').on('click', function(e) {
        e.preventDefault();
        applyFilters();
    });

    $('#see-more-btn').on('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            fetchMovies(currentPage);
        } else {
            alert('No more pages to load');
        }
    });

    // Initial fetch on page load
    fetchMovies(1, true);
});

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
