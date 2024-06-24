<?php
include_once __DIR__ . '/../config/config.php';

$apiKey = '0136e68e78a0433f8b5bdcec484af43c';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$genre = isset($_GET['genre']) ? urldecode($_GET['genre']) : '';
$year = isset($_GET['year']) ? urldecode($_GET['year']) : '';
$searchQuery = isset($_GET['query']) ? urldecode($_GET['query']) : '';

$limit = 10;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM movies WHERE 1=1";

$params = [];

if (!empty($genre) && $genre !== 'all genres') {
    $sql .= " AND listed_in LIKE :genre";
    $params[':genre'] = "%{$genre}%";
}

if (!empty($year) && $year !== 'all years') {
    if (strpos($year, '-') !== false) {
        $years = explode('-', $year);
        $sql .= " AND release_year BETWEEN :start_year AND :end_year";
        $params[':start_year'] = (int)$years[0];
        $params[':end_year'] = (int)$years[1];
    } else {
        $sql .= " AND release_year = :start_year";
        $params[':start_year'] = (int)$year;
    }
}

if (!empty($searchQuery)) {
    $sql .= " AND title LIKE :searchQuery";
    $params[':searchQuery'] = '%' . $searchQuery . '%';
}

$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

foreach ($params as $key => $value) {
    $stmt->bindParam($key, $value, PDO::PARAM_STR); 
}

$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);

$moviesHtml = '';
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

    $moviesHtml .= '
        <div class="movie-card">
            <div class="movie-id" style="display: none;">' . $movieId . '</div>
            <div class="card-head">
                <img src="' . $posterUrl . '" alt="' . $title . '" class="card-img">
                <div class="card-overlay">
                    <div class="rating"><i class="fa-solid fa-star" style="color: #f9cc6c;"></i><span>9.5</span></div>
                    <div class="addWatchList"><i class="fa-solid fa-info-circle" style="color: #fff;"></i></div>
                </div>
            </div>
            <div class="card-body">
                <h3 class="card-title">' . $title . '</h3>
                <div class="card-info"><span class="year">' . $releaseYear . '</span></div>
            </div>
        </div>';
}

echo $moviesHtml;
?>
