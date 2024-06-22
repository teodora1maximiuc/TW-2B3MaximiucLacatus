<?php
session_start();
include_once __DIR__ . '/../src/helpers/session_helper.php';
include_once __DIR__ . '/../config/config.php'; 

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
                    <li><a href="watchList.php">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
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
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
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
                    <p><a href="<?php echo BASE_URL; ?>login.php">Go to Login page.</a></p>
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
                    <li><a href="watchList.php">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
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
                <li><a href="watchList.php">WatchList</a></li>
                <li><a href="explore.php">Explore</a></li>
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
                        <div class="movie-card">
                            <div class="card-head">
                                <img src="<?php echo $movie['poster_path']; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" class="card-img">
                                <div class="card-overlay">
                                    <div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span><?php echo $movie['rating']; ?></span></div>
                                    <div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h3>
                                <div class="card-info"><span class="year"><?php echo $movie['release_year']; ?></span></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No movies found in your Watch List for category: <?php echo htmlspecialchars($category); ?></p>
                <?php endif; ?>
            </div>
        </section>
    </section>
    <script>
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
    </script>
</body>
</html>
