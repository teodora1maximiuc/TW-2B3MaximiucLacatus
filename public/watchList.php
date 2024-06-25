<?php
    session_start();
    include_once __DIR__ . '/../src/helpers/session_helper.php';
    include_once __DIR__ . '/../config/config.php'; 

    /*preluarea filmelor din baza de date pt categoria selectata */
    function getMoviesByCategory($user_id, $category) {
        global $pdo;
        try {
            $sql = "SELECT * FROM watchlist WHERE user_id = :user_id";
            $params = [':user_id' => $user_id];
            if ($category !== 'All') {
                $sql .= " AND category = :category";
                $params[':category'] = $category;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $movies = $stmt->fetchAll();
            foreach ($movies as &$movie) {
                $movieDetails = fetchMovieDetailsFromAPI($movie['movie_id']);
                if ($movieDetails) {
                    $movie['title'] = $movieDetails['title'];
                    $movie['rating'] = $movieDetails['vote_average'];
                    $movie['release_year'] = date('Y', strtotime($movieDetails['release_date']));
                    $movie['poster_path'] = 'https://image.tmdb.org/t/p/w500/' . $movieDetails['poster_path'];
                }
            }
            return $movies;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function fetchMovieDetailsFromAPI($movie_id) {
        $api_key = '0136e68e78a0433f8b5bdcec484af43c';
        $url = "https://api.themoviedb.org/3/movie/{$movie_id}?api_key={$api_key}&language=en-US";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        if (isset($data['status_code']) && $data['status_code'] == 34) {
            return null;
        }
        return $data;
    }
    if (!isset($_SESSION['user_id'])) {
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
            <title>FilmQuest</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" type="text/css" href="css/watchList.css">
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
                    <span class="title-responsive"> WatchList </span>
                    <ul class="links">
                        <li><a href="home.php">Home</a></li>
                        <li><a href="home.php#about-section">About</a></li>
                        <li><a href="watchList.php" class="active">WatchList</a></li>
                        <li><a href="explore.php">Explore</a></li>
                        <li><a href="statistics.php">Statistics</a></li>
                        <li><a href="help.php">Help</a></li>
                        <li><a href="login.php">Login</a></li>
                    </ul>
                    <div class="toggle_btn">
                        <button class="fa-solid fa-bars" style="color: #fff;"></>
                    </div>
                </div>
            </header>
            <div class="dropdown_menu">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="home.php#about-section">About</a></li>
                    <li><a href="watchList.php" class="active">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </div>
            <section class="watch-list">
                <div class="watch-list-nav">
                    <div class="list-bar">
                        <ul>
                            <li><a href="watchList.php?category=All" class="<?= ($category === 'All') ? 'active' : ''; ?>">All</a></li>
                            <li><a href="watchList.php?category=Completed" class="<?= ($category === 'Completed') ? 'active' : ''; ?>">Completed</a></li>
                            <li><a href="watchList.php?category=On Hold" class="<?= ($category === 'On Hold') ? 'active' : ''; ?>">On Hold</a></li>
                            <li><a href="watchList.php?category=Plan to Watch" class="<?= ($category === 'Plan to Watch') ? 'active' : ''; ?>">Plan to Watch</a></li>
                        </ul>
                    </div>
                    <div class="list-bar-dropdown">
                        <form action="" method="get" id="filter-form">
                            <select name="category" class="category">
                                <option value="All" <?= ($category === 'All') ? 'selected' : ''; ?>>All</option>
                                <option value="Completed" <?= ($category === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                <option value="On Hold" <?= ($category === 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                                <option value="Plan to Watch" <?= ($category === 'Plan to Watch') ? 'selected' : ''; ?>>Plan to Watch</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="not-logged-in">
                    <div class="not-logged-in-content">
                        <h2>You are not logged in.</h2>
                        <p>To see your Watch List, you need to log in first.</p>
                        <p><a href="login.php">Go to Login page.</a></p>
                    </div>
                </div>
            </section>
        </body>
        </html>
        <?php
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $category = isset($_GET['category']) ? $_GET['category'] : 'All';
    $movies = getMoviesByCategory($user_id, $category);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/watchList.css">
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
                <span class="title-responsive"> WatchList </span>
                <ul class="links">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="home.php#about-section">About</a></li>
                    <li><a href="watchList.php" class="active">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="help.php">Help</a></li>
                    <?php if(isAdmin()) : ?>
                        <li><a href="user_management.php">User Management</a></li>
                    <?php endif; ?>
                    <li><a href="../src/controllers/Users.php?q=logout">Logout</a></li>
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
                <li><a href="watchList.php" class="active">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
                <li><a href="statistics.php">Statistics</a></li>
                <li><a href="help.php">Help</a></li>
                <?php if(isAdmin()) : ?>
                    <li><a href="user_management.php">User Management</a></li>
                <?php endif; ?>
                <li><a href="../src/controllers/Users.php?q=logout">Logout</a></li>
            </ul>
        </div>
        <section class="watch-list">
            <div class="watch-list-nav">
                <div class="list-bar">
                    <ul>
                        <li><a href="watchList.php?category=All" class="<?= ($category === 'All') ? 'active' : ''; ?>">All</a></li>
                        <li><a href="watchList.php?category=Completed" class="<?= ($category === 'Completed') ? 'active' : ''; ?>">Completed</a></li>
                        <li><a href="watchList.php?category=On Hold" class="<?= ($category === 'On Hold') ? 'active' : ''; ?>">On Hold</a></li>
                        <li><a href="watchList.php?category=Plan to Watch" class="<?= ($category === 'Plan to Watch') ? 'active' : ''; ?>">Plan to Watch</a></li>
                    </ul>
                </div>
                <div class="list-bar-dropdown">
                    <form action="" method="get" id="filter-form">
                        <select name="category" class="category" onchange="this.form.submit()">
                            <option value="All" <?= ($category === 'All') ? 'selected' : ''; ?>>All</option>
                            <option value="Completed" <?= ($category === 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="On Hold" <?= ($category === 'On Hold') ? 'selected' : ''; ?>>On Hold</option>
                            <option value="Plan to Watch" <?= ($category === 'Plan to Watch') ? 'selected' : ''; ?>>Plan to Watch</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="movie-grid">
                <?php if ($movies && count($movies) > 0) : ?>
                    <?php foreach ($movies as $movie) : ?>
                        <div class="movie-card" onclick="fetchAndShowMovieDetails(<?= $movie['movie_id']; ?>)">
                            <div class="card-head">
                                <img src="<?php echo $movie['poster_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" class="card-img">
                                <div class="card-overlay">
                                    <div class="rating">
                                        <i class="fa-solid fa-star" style="color: #f9cc6c;"></i>
                                        <span><?php echo $movie['rating']; ?></span>
                                    </div>
                                    <div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                <div class="card-info"><span class="year"><?php echo $movie['release_year']; ?></span></div>
                                <div class="movie_id" style="display: none;"><?php echo $movie['movie_id']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No movies found in your Watch List for category: <?php echo htmlspecialchars($category); ?></p>
                <?php endif; ?>
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
                        <span class="watchlist-text">Change Category</span>
                    </button>
                    <div class="watchlist-dropdown-content">
                    <a href="#" onclick="changeCategory($('#modalMovieId').val(), 'Completed')">Completed</a>
                    <a href="#" onclick="changeCategory($('#modalMovieId').val(), 'On Hold')">On Hold</a>
                    <a href="#" onclick="changeCategory($('#modalMovieId').val(), 'Plan to Watch')">Plan to Watch</a>
                    </div>
                </div>
                <a href="aboutMovie.php" class="statistic-button" id="modalStatisticLink">Statistic</a>
                <a href="#" class="remove-button" onclick="removeFromWatchlist($('#modalMovieId').val())">Remove</a>
                </div>
            </div>
        </div>
        <span class="close">&times;</span>
    </div>
    <input type="hidden" id="modalMovieId"> 
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        $(document).ready(function() {
            const toggleBtn = document.querySelector('.toggle_btn');
            const toggleBtnIcon = document.querySelector('.toggle_btn i');
            const dropDownMenu = document.querySelector('.dropdown_menu');

            toggleBtn.onclick = function() {
                dropDownMenu.classList.toggle('open');
                const isOpen = dropDownMenu.classList.contains('open');
                toggleBtnIcon.classList = isOpen ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
            };
        });

        /*preluare informatii filme din api tmdb */
        function fetchAndShowMovieDetails(movieId) {
        console.log('Fetching movie details for ID:', movieId);
        const apiKey = '0136e68e78a0433f8b5bdcec484af43c';
        const apiUrl = `https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=en-US`;
        const trailerUrl = `https://api.themoviedb.org/3/movie/${movieId}/videos?api_key=${apiKey}&language=en-US`;

        $.ajax({
            url: apiUrl,
            type: 'GET',
            success: function(data) {
                if (data.title) {
                    const title = data.title;
                    const poster = `https://image.tmdb.org/t/p/w500/${data.poster_path}`;
                    const year = data.release_date ? new Date(data.release_date).getFullYear() : 'N/A';
                    const description = data.overview || 'No description available';
                    console.log('Movie details:', title, poster, year, description);
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
        console.log('Showing modal for:', title, year, description);
        const modal = $('#movieModal');
        modal.find('.modal-content #modalTitle').text(title);
        modal.find('.modal-content #modalYear').text(year);
        modal.find('.modal-content #modalDescription').text(description);
        $('#modalMovieId').val(movieId);
        $('#modalStatisticLink').attr('href', `aboutMovie.php?id=${movieId}`);
        modal.css('display', 'block');
    }

    function closeModal() {
        $('#movieModal').css('display', 'none');
        $('#modalTrailer').attr('src', '');
        const movieId = $('#modalMovieId').val();
        const newCategory = $('.watchlist-dropdown-content .active').text();
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

    /*schimb categorie folosind ajax */
    function changeCategory(movieId, newCategory) {
        console.log('Changing category for movie ID:', movieId, 'to:', newCategory);
        $.ajax({
            url: '/TW-2B3MaximiucLacatus/src/views/change_category.php',
            type: 'POST',
            dataType: 'json',
            data: {
                movie_id: movieId,
                new_category: newCategory
            },
            success: function(response) {
                if (response.success) {
                    alert('Category changed successfully!');
                    closeModal(); 
                    window.location.href = 'watchList.php?category=' + encodeURIComponent(newCategory);
                } else {
                    alert('Failed to change category. ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error changing category:', error);
                alert('Failed to change category. Please try again later.');
            }
        });
    }

    /*eliminarea unui film din watchlist folosind ajax */
    function removeFromWatchlist(movieId) {
        console.log('Removing movie from watchlist with ID:', movieId);
        $.ajax({
            url: '/TW-2B3MaximiucLacatus/src/views/remove_movie.php', 
            type: 'POST',
            dataType: 'json',
            data: {
                movie_id: movieId
            },
            success: function(response) {
                if (response.status === 'success') {
                    alert('Movie removed successfully!');
                    closeModal(); 
                    window.location.reload(); 
                } else {
                    alert('Failed to remove movie. ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error removing movie:', error);
                alert('Failed to remove movie. Please try again later.');
            }
        });
    }
</script>
</body>
</html>