<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../src/helpers/session_helper.php';
$movieId = $_GET['id'];
$apiKey = '0136e68e78a0433f8b5bdcec484af43c';
$movieUrl = "https://api.themoviedb.org/3/movie/{$movieId}?api_key={$apiKey}&language=en-US&append_to_response=credits";
    
$movieResponse = file_get_contents($movieUrl);
$movieData = json_decode($movieResponse, true);
    
if ($movieData && isset($movieData['credits']['cast'])) {
    $actors = $movieData['credits']['cast'];
} else {
    $actors = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/aboutMovie.css">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <section class="section1">
        <header>
            <div class="navbar">
                <a href="#" class="logo">
                    <img src="images/logo.png" alt="FilmQuest Logo" class="logo-img">
                </a>
                <!-- <span class="title-responsive"> Explore </span> -->
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
        <div class="Information">
            <!-- <?php
                $movieId = $_GET['id'];
            ?> -->
            <div class="movie">
                <div class="image">
                    <img id="movie-image" src="path_to_movie_image.jpg" alt="Movie Image">
                </div>
                <div class = "movie-details">
                        <h2 id="movie-title">Movie Title</h2>
                    <p id="movie-description">Description of the movie.</p>
                </div>
            </div>
            <div class="Actors">
                <h3 class="Actors-title">Actors</h3>
                <div class="actors-container">
                    <?php if (!empty($actors)) : ?>
                        <?php foreach ($actors as $actor) : ?>
                            <?php
                                $actorName = $actor['name'];
                                $character = $actor['character'];
                                $photoPath = 'https://image.tmdb.org/t/p/w500/' . $actor['profile_path'];
                            ?>
                            <div class="actor-card">
                                <img src="<?php echo $photoPath; ?>" alt="<?php echo $actorName; ?>" class="card-img">
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo $actorName; ?></h3>
                                    <div class="card-info">
                                        <span class="character"><?php echo $character; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No actors found for this movie.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="Statistics">
                <h3>Statistics</h3>
                <div id="movie-stats">
                </div>
            </div>
            <div class="Rating">
                <h3>Rating Trend</h3>
                <div class="chart" id="rating-trend"></div>
            </div>
            <div class="Performance">
                <h3>Box Office Performance</h3>
                <div class="chart" id="box-office-performance"></div>
            </div>
        </div>
    </section>
    <script>
        const apiKey = '0136e68e78a0433f8b5bdcec484af43c';
        const movieId = '<?php echo $movieId; ?>'; 

        fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                displayMovieInfo(data);
                displayMovieStats(data);
            })
            .catch(error => console.error('Error fetching movie data:', error));

        function displayMovieInfo(data) {
            document.getElementById('movie-title').textContent = data.title;
            document.getElementById('movie-image').src = `https://image.tmdb.org/t/p/w500${data.poster_path}`;
            document.getElementById('movie-description').textContent = data.overview;

            const actorsList = document.getElementById('actors-list');
            actorsList.innerHTML = '';
            data.credits.cast.slice(0, 5).forEach(actor => {
                const li = document.createElement('li');
                li.textContent = actor.name;
                actorsList.appendChild(li);
            });
        }

        function displayMovieStats(data) {
            const statsDiv = document.getElementById('movie-stats');
            statsDiv.innerHTML = `
                <p>Release Date: ${data.release_date}</p>
                <p>Genres: ${data.genres.map(genre => genre.name).join(', ')}</p>
                <p>Runtime: ${data.runtime} minutes</p>
                <p>Budget: $${data.budget.toLocaleString()}</p>
                <p>Revenue: $${data.revenue.toLocaleString()}</p>
                <p>Popularity Score: ${data.popularity}</p>
            `;
        }
    </script>
</body>
</html>
