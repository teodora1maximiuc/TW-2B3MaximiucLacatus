<?php
$servername = "sql7.freesqldatabase.com";
$username = "sql7714801";
$password = "Rp7WnGbdeH";
$dbname = "sql7714801";
$apiKey = '0136e68e78a0433f8b5bdcec484af43c'; 
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . $conn->error;
    $conn->close();
    die();
}
$conn->select_db($dbname);
$sql = "SELECT show_id, title FROM movies WHERE poster_path IS NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $show_id = $row["show_id"];
        $title = urlencode($row["title"]);

        $apiUrl = "https://api.themoviedb.org/3/search/movie?api_key={$apiKey}&query={$title}";
        $apiResponse = file_get_contents($apiUrl);
        $apiData = json_decode($apiResponse, true);

        if ($apiData && isset($apiData['results'][0]['poster_path'])) {
            $posterPath = $apiData['results'][0]['poster_path'];
            $apiId = $apiData['results'][0]['id'];

            $updateSql = "UPDATE movies SET poster_path = ?, api_id = ? WHERE show_id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("sis", $posterPath, $apiId, $show_id);

            if ($stmt->execute()) {
                echo "Updated poster_path and api_id for show_id: $show_id<br>";
            } else {
                echo "Error updating poster_path and api_id for show_id: $show_id - " . $stmt->error . "<br>";
            }

            $stmt->close();
        } else {
            echo "Poster path not found for title: {$row['title']}<br>";
        
            $deleteSql = "DELETE FROM movies WHERE show_id = ?";
            $stmt = $conn->prepare($deleteSql);
            $stmt->bind_param("s", $show_id);

            if ($stmt->execute()) {
                echo "Deleted movie without poster_path for show_id: $show_id<br>";
            } else {
                echo "Error deleting movie for show_id: $show_id - " . $stmt->error . "<br>";
            }

            $stmt->close();
        }
    }
} else {
    echo "No movies found without poster_path.<br>";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload CSV</title>
</head>
<body>
</body>
</html>
