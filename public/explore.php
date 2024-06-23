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
    $actorSearchUrl = 'https://api.themoviedb.org/3/search/person?api_key=' . $apiKey . '&query=' . urlencode($actor);
    $actorResponse = file_get_contents($actorSearchUrl);
    $actorData = json_decode($actorResponse, true);
    
    if (isset($actorData['results'][0]['id'])) {
        $actorId = $actorData['results'][0]['id'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                    <div class="apply-filters-btn">
                        <button class="button" onclick="applyFilters()">Apply Filters</button>
                    </div>
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
                foreach ($data['cast'] as $movie) {
                    $title = $movie['title'];
                    $posterPath = 'https://image.tmdb.org/t/p/w500/' . $movie['poster_path'];
                    $releaseYear = date('Y', strtotime($movie['release_date']));
                    $rating = $movie['vote_average'];

                    echo '<div class="movie-card">';
                    echo '<div class="movie-id" style="display: none;">' . $movieId . '</div>';
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
                    $movieId = $movie['id'];

                    echo '<div class="movie-card">';
                    echo '<div class="movie-id" style="display: none;">' . $movieId . '</div>';	
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
        <div class="see-more-btn">
            <button id="seeMoreButton" class="seeMoreButton" onclick="loadMore()">See More</button>
        </div>
    </section>
</section>

<div id="movieModal" class="modal">
    <div class="modal-content">
        <div class="trailer">
            <iframe id="modalTrailer" width="560" height="315" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="modal-details">
            <div class="modal-info">
                <h2 id="modalTitle"></h2>
                <p id="modalYear"></p>
                <p id="modalDescription"></p>
                <a href="aboutMovie.php" id="modalStatisticLink">Statistic</a>
            </div>
        </div>
        <span class="close">&times;</span>
    </div>
    <input type="hidden" id="modalMovieId"> 
</div>

<script>
$(document).ready(function() {
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
                $('.movies-grid').html('');
            }

            let moviesHtml = '';
            if (data.results.length > 0) {
                data.results.forEach(function(movie) {
                    const title = movie.title;
                    const posterPath = 'https://image.tmdb.org/t/p/w500/' + movie.poster_path;
                    const releaseYear = new Date(movie.release_date).getFullYear();
                    const rating = movie.vote_average;
                    const movieId = movie.id;
                    console.log(movieId);

                    moviesHtml += '<div class="movie-card" >';
                    moviesHtml += '<div class="movie-id" style="display: none;">' + movieId + '</div>';
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
                $('#seeMoreButton').hide();
            } else {
                $('#seeMoreButton').show();
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
    $('#seeMoreButton').on('click', loadMore);
    function loadMore() {
        if (currentPage < totalPages) {
            currentPage++;
            fetchMovies(currentPage);
        } else {
            alert('No more pages to load');
        }
    }

    fetchMovies(1, true);
    function fetchMovieDetailsByTitle(movieTitle, movieId) {
        const apiKey = '0136e68e78a0433f8b5bdcec484af43c';
        const apiUrl = 'https://api.themoviedb.org/3/search/movie?api_key=0136e68e78a0433f8b5bdcec484af43c' + '&query=' + encodeURIComponent(movieTitle);
        const trailerUrl = `https://api.themoviedb.org/3/movie/${movieId}/videos?api_key=${apiKey}&language=en-US`;
        $.ajax({
            url: apiUrl,
            type: 'GET',
            success: function(data) {
                if (data.results.length > 0) {
                    const movie = data.results[0];
                    const title = movie.title;
                    const poster = 'https://image.tmdb.org/t/p/w500' + movie.poster_path;
                    const year = movie.release_date ? new Date(movie.release_date).getFullYear() : 'N/A';
                    const description = movie.overview ? movie.overview : 'No description available';

                    showModal(title, poster, year, description, movieId);

                    $.get(trailerUrl, function(response) {
                        if (response.results.length > 0) {
                            const trailerKey = response.results[0].key;
                            const trailerUrl = `https://www.youtube.com/embed/${trailerKey}`;

                            $('#modalTrailer').attr('src', trailerUrl);
                        }
                    });
                } else {
                    alert('Movie details not found.');
                }
            },
            error: function(err) {
                console.error('Error fetching movie details:', err);
                alert('Failed to fetch movie details. Please try again later.');
            }
        });
    }
    function showModal(title, poster, year, description, movieId) {
        const modal = $('#movieModal');
        modal.find('.modal-content #modalTitle').text(title);
        modal.find('.modal-content #modalYear').text(`Year: ${year}`);
        modal.find('.modal-content #modalDescription').text(description);
        $('#modalMovieId').val(movieId); 
        $('#modalStatisticLink').attr('href', 'aboutMovie.php?id=' + movieId); 
        modal.css('display', 'block');
    }
    function closeModal() {
        $('#movieModal').css('display', 'none');
        $('#modalTrailer').attr('src', '');
    }

    $(document).on('click', '.movie-card', function() {
        const movieTitle = $(this).find('.card-title').text(); 
        const movieId = $(this).find('.movie-id').text();
        fetchMovieDetailsByTitle(movieTitle, movieId);
    });

    $(document).on('click', '.modal .close', function() {
        closeModal();
    });

    // Close modal when clicking outside of it
    $(window).on('click', function(event) {
        const modal = $('#movieModal');
        if (event.target == modal[0]) {
            closeModal();
        }
    });
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
