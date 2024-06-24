<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include_once __DIR__ . '/../src/helpers/session_helper.php';
    require_once __DIR__ . '/../config/config.php';

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
            <li><a href="home.php">Home</a></li>
            <li><a href="home.php#about-section">About</a></li>
            <li><a href="watchList.php">WatchList</a></li>
            <li><a href="explore.php" class="active">Explore</a></li>
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
                <form action="" method="get" id="filter-form" class="filters">
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
                <h3 id="modalYear"></h3>
                <p id="modalDescription"></p>
                <div class="button-container">
                <div class="watchlist-dropdown">
                    <button class="watchlist-btn" onclick="toggleWatchlistDropdown(this)">
                        <i class="fa-solid fa-plus"></i>
                        <span class="watchlist-text">Add to Watchlist</span>
                    </button>
                    <div class="watchlist-dropdown-content">
                        <a href="#" onclick="addToWatchlist(this, 'Completed')">Completed</a>
                        <a href="#" onclick="addToWatchlist(this, 'On Hold')">On Hold</a>
                        <a href="#" onclick="addToWatchlist(this, 'Plan to Watch')">Plan to Watch</a>
                    </div>
                </div>
                <a href="aboutMovie.php" class="statistic-button" id="modalStatisticLink">Statistic</a>
                </div>
            </div>
        </div>
        <span class="close">&times;</span>
    </div>
    <input type="hidden" id="modalMovieId"> 
</div>

<script>
    $(document).ready(function() {
        $('.watchlist-btn').click(function(e) {
            e.preventDefault();
            $(this).parent().toggleClass('active');
        });
        
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.addWatchList').length) {
                $('.addWatchList').removeClass('active');
            }
        });
    });

    function toggleWatchlistDropdown(button) {
        const dropdownContent = $(button).siblings('.watchlist-dropdown-content');
        dropdownContent.toggle();

        const isOpen = dropdownContent.is(':visible');
        const icon = $(button).find('i');

        if (isOpen) {
            icon.removeClass('fa-plus').addClass('fa-check');
        } else {
            icon.removeClass('fa-check').addClass('fa-plus');
        }
    }
    /*functie de adaugare a unui film in watchlist in categoria selectata */
    function addToWatchlist(link, option) {
        const selectedOptionText = option;
        const movieId = $('#modalMovieId').val();

        $.ajax({
            url: '/TW-2B3MaximiucLacatus/src/views/add_to_watchlist.php',
            type: 'POST',
            data: { movie_id: movieId, category: selectedOptionText },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    const watchlistButton = $('.watchlist-btn');
                    watchlistButton.find('span.watchlist-text').text(selectedOptionText);
                    watchlistButton.find('i').removeClass('fa-plus').addClass('fa-check');
                    watchlistButton.siblings('.watchlist-dropdown-content').hide();
                } else {
                    alert(response.message);
                    resetWatchlistButton();
                }
            },
            error: function(xhr, status, error) {
                alert('Error adding movie to watchlist. Please try again later.');
                console.error(xhr.responseText);
            }
        });
    }

    function resetWatchlistButton() {
        const button = $('.watchlist-btn');
        const icon = button.find('i');
        const text = button.find('.watchlist-text');

        icon.removeClass('fa-check').addClass('fa-plus');
        text.text('Add to Watchlist');
        $('.watchlist-dropdown-content').hide();
    }

    $(document).ready(function() {
        let currentPage = 1;
        let totalPages = 10;

<<<<<<< Updated upstream
        function performSearch() {
            const searchInput = document.getElementById('searchInput').value.trim();
            const searchUrl = 'explore.php?title=' + encodeURIComponent(searchInput);
            window.location.href = searchUrl;
=======
    $(document).on('click', '.modal .close', function() {
        closeModal();
    });
    $(window).on('click', function(event) {
        const modal = $('#movieModal');
        if (event.target == modal[0]) {
            closeModal();
>>>>>>> Stashed changes
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
            modal.find('.modal-content #modalYear').text(`${year}`);
            modal.find('.modal-content #modalDescription').text(description);
            $('#modalMovieId').val(movieId); 
            $('#modalStatisticLink').attr('href', 'aboutMovie.php?id=' + movieId); 
            modal.css('display', 'block');
        }
        function closeModal() {
            $('#movieModal').css('display', 'none');
            $('#modalTrailer').attr('src', '');
            resetWatchlistButton();
        }

        $(document).on('click', '.movie-card', function() {
            const movieTitle = $(this).find('.card-title').text(); 
            const movieId = $(this).find('.movie-id').text();
            fetchMovieDetailsByTitle(movieTitle, movieId);
        });

        $(document).on('click', '.modal .close', function() {
            closeModal();
        });

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


<!-- <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../src/helpers/session_helper.php';
include_once __DIR__ . '/../config/config.php';

$apiKey = '0136e68e78a0433f8b5bdcec484af43c';
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : 'all genres';
$year = isset($_GET['year']) ? $_GET['year'] : 'all years';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$actor = isset($_GET['actor']) ? $_GET['actor'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$moviesPerPage = 10;
$offset = ($page - 1) * $moviesPerPage;

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sql = "SELECT * FROM movies WHERE 1=1";
    $params = [];

    if ($genre !== 'all genres') {
        $sql .= " AND listed_in LIKE :genre";
        $params[':genre'] = '%' . $genre . '%';
    }
    if ($year !== 'all years') {
        if (strpos($year, '-') !== false) {
            list($startYear, $endYear) = explode('-', $year);
            $sql .= " AND release_year BETWEEN :startYear AND :endYear";
            $params[':startYear'] = $startYear;
            $params[':endYear'] = $endYear;
        } else {
            $sql .= " AND release_year = :year";
            $params[':year'] = $year;
        }
    }
    if (!empty($searchQuery)) {
        $sql .= " AND title LIKE :searchQuery";
        $params[':searchQuery'] = '%' . $searchQuery . '%';
    }
    if (!empty($title)) {
        $sql .= " AND title LIKE :title";
        $params[':title'] = '%' . $title . '%';
    }
    if (!empty($actor)) {
        $sql .= " AND cast LIKE :actor";
        $params[':actor'] = '%' . $actor . '%';
    }
    $sql .= " LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->bindParam(':limit', $moviesPerPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT); 

    $stmt->execute();
    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);


    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
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
            <li><a href="home.php">Home</a></li>
            <li><a href="home.php#about-section">About</a></li>
            <li><a href="watchList.php">WatchList</a></li>
            <li><a href="explore.php" class="active">Explore</a></li>
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
                <form action="" method="get" id="filter-form" class="filters">
                    <select name="genre" class="genre">
                        <option value="all genres" <?php echo ($genre === 'all genres') ? 'selected' : ''; ?>>All genres</option>
                        <option value="Animation" <?php echo ($genre === 'Animation') ? 'selected' : ''; ?>>Animation</option>
                        <option value="Action" <?php echo ($genre === 'Action') ? 'selected' : ''; ?>>Action</option>
                        <option value="Adventure" <?php echo ($genre === 'Adventure') ? 'selected' : ''; ?>>Adventure</option>
                        <option value="Comedy" <?php echo ($genre === 'Comedy') ? 'selected' : ''; ?>>Comedy</option>
                        <option value="Crime" <?php echo ($genre === 'Crime') ? 'selected' : ''; ?>>Crime</option>
                        <option value="Documentary" <?php echo ($genre === 'Documentary') ? 'selected' : ''; ?>>Documentary</option>
                        <option value="Drama" <?php echo ($genre === 'Drama') ? 'selected' : ''; ?>>Drama</option>
                        <option value="Fantasy" <?php echo ($genre === 'Fantasy') ? 'selected' : ''; ?>>Fantasy</option>
                        <option value="Romance" <?php echo ($genre === 'Romance') ? 'selected' : ''; ?>>Romance</option>
                    </select>
                    <select name="year" class="year">
                        <option value="all years" <?php echo ($year === 'all years') ? 'selected' : ''; ?>>All years</option>
                        <option value="2024" <?php echo ($year === '2024') ? 'selected' : ''; ?>>2024</option>
                        <option value="2023" <?php echo ($year === '2023') ? 'selected' : ''; ?>>2023</option>
                        <option value="2022" <?php echo ($year === '2022') ? 'selected' : ''; ?>>2022</option>
                        <option value="2021" <?php echo ($year === '2021') ? 'selected' : ''; ?>>2021</option>
                        <option value="2020" <?php echo ($year === '2020') ? 'selected' : ''; ?>>2020</option>
                        <option value="2010-2020" <?php echo ($year === '2010-2020') ? 'selected' : ''; ?>>2010-2020</option>
                        <option value="2000-2010" <?php echo ($year === '2000-2010') ? 'selected' : ''; ?>>2000-2010</option>
                        <option value="1990-2000" <?php echo ($year === '1990-2000') ? 'selected' : ''; ?>>1990-2000</option>
                    </select>
                    <div class="search">
                        <input type="text" name="query" class="input" placeholder="Search by title, actor" value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <button type="submit" class="button">Search</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="movies-grid">
            <?php
            foreach ($movies as $movie) {
                $title = htmlspecialchars($movie['title']);
                $releaseYear = htmlspecialchars($movie['release_year']);
                $genre = htmlspecialchars($movie['listed_in']);
                $duration = htmlspecialchars($movie['duration']);
                $movieId = htmlspecialchars($movie['api_id']);
                $tmdbApiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query=" . urlencode($title);
                $tmdbResponse = file_get_contents($tmdbApiUrl);
                $tmdbData = json_decode($tmdbResponse, true);
                $posterPath = $tmdbData['results'][0]['poster_path'] ?? null;
                $posterUrl = $posterPath ? "https://image.tmdb.org/t/p/w500" . $posterPath : 'images/no_poster_available.jpg';
            ?>
            <div class="movie-card">
                <div class="movie-id" style="display: none;"><?php echo $movieId?></div>
                <div class="card-head">
                    <img src="<?php echo $posterUrl; ?>" alt="<?php echo $title; ?>" class="card-img">
                    <div class="card-overlay">
                        <div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>9.5</span></div>
                        <div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="card-title"><?php echo $title; ?></h3>
                    <div class="card-info"><span class="year"><?php echo $releaseYear; ?></span></div>
                </div>
            </div>
            <?php
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
    var currentPage = <?php echo $page; ?>; 

    $(document).ready(function() {
    function showModal(title, releaseYear, description, movieId) {
        const modal = $('#movieModal');
        modal.find('.modal-content #modalTitle').text(title);
        modal.find('.modal-content #modalYear').text(`Year: ${releaseYear}`);
        modal.find('.modal-content #modalDescription').text(description);
        $('#modalMovieId').val(movieId);
        $('#modalStatisticLink').attr('href', 'aboutMovie.php?id=' + movieId); 
        modal.css('display', 'block');
    }
    $(document).on('click', '.movie-card', function() {
        const movieTitle = $(this).find('.card-title').text().trim();
        const releaseYear = $(this).find('.year').text().trim();
        const description = ''; 
        const movieId = $(this).find('.movie-id').text().trim();
        showModal(movieTitle, releaseYear, description, movieId);
    });
    function closeModal() {
        $('#movieModal').css('display', 'none');
        $('#modalTrailer').attr('src', ''); 
    }
    $(document).on('click', '.modal .close', function() {
        closeModal();
    });

    $(window).on('click', function(event) {
        const modal = $('#movieModal');
        if (event.target == modal[0]) {
            closeModal();
        }
    });
});


    function loadMore() {
        currentPage++; 

        $.ajax({
            url: 'load_more.php',
            type: 'GET',
            data: $('#filter-form').serialize() + '&page=' + currentPage,
            success: function(response) {
                console.log('Response:', response);
                $('.movies-grid').append(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading more movies:', error);
            }
        });
    }
</script>

</body>
</html> -->
