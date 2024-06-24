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
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/aboutMovie.css">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <section class="section1">
        <header>
            <div class="navbar">
                <a href="#" class="logo">
                    <img src="images/logo.png" alt="FilmQuest Logo" class="logo-img">
                </a>
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
        <div class="Information">
            <div class="movie">
                <div class="image">
                    <img id="movie-image" alt="Movie Image">
                </div>
                <div class="movie-details">
                    <h2 id="movie-title">Movie Title</h2>
                    <p id="movie-description">Description of the movie.</p>
                    <p id="movie-rating"><i class="fas fa-star" style="color: gold;"></i> Rating: </p>
                </div>
            </div>
            <div class="Actors">
                <h3 class="Actors-title">Actors</h3>
                <div class="actors-container" id="actors-container"></div>
            </div>
            <div class="more-details">
                <div class="Statistics">
                    <h3>Details</h3>
                    <div id="movie-stats"></div>
                </div>
                <div class="Performance">
                    <h3>Box Office Performance</h3>
                    <div class="chart" id="box-office-performance">
                        <canvas id="boxOfficeChart"></canvas> 
                        <button id="exportBoxSVG">Export as SVG</button>
                        <button id="exportBoxWebP">Export as WebP</button>
                        <button id="exportBoxCSV">Export as CSV</button>
                    </div>
                </div>
                <div class="Genres">
                    <h3>Genres</h3>
                    <div class="chart" id="genres-chart">
                        <canvas id="genresDonutChart"></canvas>
                        <button id="exportGenresSVG">Export as SVG</button>
                        <button id="exportGenresWebP">Export as WebP</button>
                        <button id="exportGenresCSV">Export as CSV</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        const apiKey = '0136e68e78a0433f8b5bdcec484af43c';
        const movieId = '<?php echo $movieId; ?>';

        fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&append_to_response=credits`)
            .then(response => response.json())
            .then(data => {
                displayMovieInfo(data);
                displayMovieStats(data);
                createBoxOfficeChart(data.budget, data.revenue);
                createGenresDonutChart(data.genres);
            })
            .catch(error => console.error('Error fetching movie data:', error));

        function displayMovieInfo(data) {
            document.getElementById('movie-title').textContent = data.title;
            document.getElementById('movie-image').src = `https://image.tmdb.org/t/p/w500${data.poster_path}`;
            document.getElementById('movie-description').textContent = data.overview;
            const ratingElement = document.getElementById('movie-rating');
            ratingElement.innerHTML = `<i class="fas fa-star"></i> Rating: ${data.vote_average}`;

            const actorsContainer = document.getElementById('actors-container');
            actorsContainer.innerHTML = '';
            data.credits.cast.slice(0, 5).forEach(actor => {
                const actorCard = document.createElement('div');
                actorCard.className = 'actor-card';
                actorCard.innerHTML = `
                    <img src="https://image.tmdb.org/t/p/w500${actor.profile_path}" alt="${actor.name}" class="card-img">
                    <div class="card-body">
                        <h3 class="card-title">${actor.name}</h3>
                        <div class="card-info">
                            <span class="character">${actor.character}</span>
                        </div>
                    </div>
                `;
                actorsContainer.appendChild(actorCard);
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

        function createBoxOfficeChart(budget, revenue) {
            const ctx = document.getElementById('boxOfficeChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Budget', 'Revenue'],
                    datasets: [{
                        label: 'Amount in USD',
                        data: [budget, revenue],
                        backgroundColor: ['#FF6384', '#36A2EB'],
                        borderColor: ['#FF6384', '#36A2EB'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            document.getElementById('exportBoxSVG').onclick = function () {
                const ctx = document.getElementById('boxOfficeChart');
                const chart = Chart.getChart(ctx);
                if (chart) {
                    const svg = chartToSVG(chart);
                    const blob = new Blob([svg], { type: 'image/svg+xml' });
                    const url = URL.createObjectURL(blob);
                    downloadFile(url, 'box_office_chart.svg');
                }
            };

            document.getElementById('exportBoxWebP').onclick = function () {
                const canvas = document.querySelector('#boxOfficeChart');
                canvas.toBlob(function (blob) {
                    const url = URL.createObjectURL(blob);
                    downloadFile(url, 'box_office_chart.webp');
                }, 'image/webp');
            };

            document.getElementById('exportBoxCSV').onclick = function () {
                const csv = chartToCSV(boxOfficeChart);
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'box_office_chart.csv');
            };
        }

        function createGenresDonutChart(genres) {
            const genresLabels = genres.map(genre => genre.name);
            const totalGenres = genresLabels.length;
            const genresData = genresLabels.map((_, index) => (totalGenres - index) * (100 / totalGenres));

            const ctx = document.getElementById('genresDonutChart').getContext('2d');
            const genresChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: genresLabels,
                    datasets: [{
                        data: genresData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    if (genresData && genresData.length > tooltipItem.index) {
                                        return `${genresLabels[tooltipItem.index]}: ${genresData[tooltipItem.index].toFixed(2)}%`;
                                    }
                                    return '';
                                }
                            }
                        }
                    }
                }
            });

            document.getElementById('exportGenresSVG').onclick = function () {
                const svg = chartToSVG(genresChart);
                const blob = new Blob([svg], { type: 'image/svg+xml' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'genres_chart.svg');
            };

            document.getElementById('exportGenresWebP').onclick = function () {
                const canvas = document.querySelector('#genresDonutChart');
                canvas.toBlob(function (blob) {
                    const url = URL.createObjectURL(blob);
                    downloadFile(url, 'genres_chart.webp');
                }, 'image/webp');
            };

            document.getElementById('exportGenresCSV').onclick = function () {
                const csv = chartToCSV(genresChart);
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'genres_chart.csv');
            };
        }
        
        function chartToSVG(chart) {
            if (!chart || !chart.data || !chart.data.labels || !chart.data.datasets) {
                console.error('Invalid chart object');
                return '';
            }

            const width = chart.width;
            const height = chart.height;
            const labels = chart.data.labels;
            const datasets = chart.data.datasets;
            const maxDataValue = Math.max(...datasets[0].data);

            let svg = `<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">`;
            svg += `<rect width="100%" height="100%" fill="white"/>`;
            svg += `<g transform="translate(0, ${height}) scale(1, -1)">`;

            if (chart.config.type === 'bar') {
                const barWidth = width / labels.length;
                labels.forEach((label, index) => {
                    datasets.forEach((dataset, datasetIndex) => {
                        const x = index * barWidth;
                        const barHeight = (dataset.data[index] / Math.max(...dataset.data)) * height;
                        const color = dataset.backgroundColor[index];
                        svg += `<rect x="${x}" y="0" width="${barWidth - 1}" height="${barHeight}" fill="${color}" />`;
                    });
                });
            } else if (chart.config.type === 'line') {
                let points = "";
                labels.forEach((label, index) => {
                    const x = index * (width / labels.length);
                    const y = (datasets[0].data[index] / maxDataValue) * height;
                    points += `${x},${y} `;
                });
                svg += `<polyline fill="none" stroke="${datasets[0].borderColor}" stroke-width="${datasets[0].borderWidth}" points="${points}" />`;
            }

            svg += `</g>`;
            svg += `</svg>`;
            return svg;
        }
        function chartToCSV(chart) {
            const datasets = chart.data.datasets;
            const labels = chart.data.labels;
            let csv = 'Label,Value\n';
            labels.forEach((label, index) => {
                datasets.forEach((dataset) => {
                    csv += `${label},${dataset.data[index]}\n`;
                });
            });
            return csv;
        }

        function downloadFile(dataUrl, filename) {
            const a = document.createElement('a');
            a.href = dataUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
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
