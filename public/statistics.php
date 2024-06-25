<?php
    session_start();
    include_once __DIR__ . '/../src/helpers/session_helper.php';
    include_once __DIR__ . '/../config/config.php';
    
    $query = "SELECT release_year, COUNT(*) as count FROM movies GROUP BY release_year";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $movies_per_year = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $query = "SELECT duration FROM movies";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $movies_duration = $stmt->fetchAll(PDO::FETCH_ASSOC);
    function durationToMinutes($duration) {
        preg_match('/(\d+) min/', $duration, $matches);
        return isset($matches[1]) ? (int)$matches[1] : 0;
    }

    foreach ($movies_duration as &$movie) {
        $movie['duration_minutes'] = durationToMinutes($movie['duration']);
    }
    unset($movie); 

    $durationMap = [];
    foreach ($movies_duration as $movie) {
        $duration = $movie['duration_minutes'];
        if (!isset($durationMap[$duration])) {
            $durationMap[$duration] = 0;
        }
        $durationMap[$duration]++;
    }

    $lineChartLabels = array_keys($durationMap);
    sort($lineChartLabels, SORT_NUMERIC);
    $lineChartData = array_values($durationMap);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/statistics.css">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="explore.php">Explore</a></li>
                    <li><a href="statistics.php" class="active">Statistics</a></li>
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
            <li>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="home.php#about-section">About</a></li>
                    <li><a href="watchList.php">WatchList</a></li>
                    <li><a href="explore.php">Explore</a></li>
                    <li><a href="statistics.php" class="active">Statistics</a></li>
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
    <div class="continut">
        <div class="title">
            <h1>Statistics</h1>
        </div>
        <div class="container">
            <div class="chart-container">
                <div style="flex: 1;">
                    <canvas id="barChart"></canvas>
                    <button id="exportBarSVG">Export as SVG</button>
                    <button id="exportBarWebP">Export as WebP</button>
                    <button id="exportBarCSV">Export as CSV</button>
                </div>
                <div style="flex: 1;">
                    <canvas id="lineChart"></canvas>
                    <button id="exportLineSVG">Export as SVG</button>
                    <button id="exportLineWebP">Export as WebP</button>
                    <button id="exportLineCSV">Export as CSV</button>
                </div>
            </div>
        </div>
    </div>
        <script>
            const moviesPerYear = <?php echo json_encode($movies_per_year); ?>;
            const moviesDuration = <?php echo json_encode($movies_duration); ?>;

            const barLabels = moviesPerYear.map(item => item.release_year);
            const barData = moviesPerYear.map(item => item.count);

            const lineLabels = moviesDuration.map(item => item.release_year);
            const lineData = moviesDuration.map(item => item.duration_minutes);

            const barCtx = document.getElementById('barChart');
            const pastelColors = ['#FFB3BA', '#BAE1FF', '#FFFFBA', '#BAFFC9', '#D1BAFF', '#FFD6A5'];

            const barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: barLabels,
                    datasets: [{
                        label: '# of Movies per year',
                        data: barData,
                        borderWidth: 1,
                        backgroundColor: pastelColors
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            const lineCtx = document.getElementById('lineChart');
            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($lineChartLabels); ?>,
                    datasets: [{
                        label: '# of Movies with a duration',
                        data: <?php echo json_encode($lineChartData); ?>,
                        borderColor: pastelColors[1],
                        backgroundColor: pastelColors[1],
                        borderWidth: 2,
                        fill: false,
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'linear', 
                            title: {
                                display: true,
                                text: 'Duration (minutes)'
                            },
                            ticks: {
                                stepSize: 10
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Number of Movies'
                            },
                            beginAtZero: true
                        }
                    }
                }
            });

            document.getElementById('exportBarSVG').onclick = function() {
                const svg = chartToSVG(barChart);
                const blob = new Blob([svg], { type: 'image/svg+xml' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'bar_chart.svg');
            };

            document.getElementById('exportBarWebP').onclick = function() {
                const canvas = document.querySelector('#barChart');
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    downloadFile(url, 'bar_chart.webp');
                }, 'image/webp');
            };

            document.getElementById('exportBarCSV').onclick = function() {
                const csv = chartToCSV(barChart);
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'bar_chart.csv');
            };

            document.getElementById('exportLineSVG').onclick = function() {
                const svg = chartToSVG(lineChart);
                const blob = new Blob([svg], { type: 'image/svg+xml' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'line_chart.svg');
            };
            document.getElementById('exportLineWebP').onclick = function() {
                const canvas = document.querySelector('#lineChart');
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    downloadFile(url, 'line_chart.webp');
                }, 'image/webp');
            };
            document.getElementById('exportLineCSV').onclick = function() {
                const csv = chartToCSV(lineChart);
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                downloadFile(url, 'line_chart.csv');
            };
            function downloadFile(dataUrl, filename) {
                const a = document.createElement('a');
                a.href = dataUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
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

            function chartToSVG(chart) {
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
                            const color = dataset.backgroundColor[index%6];
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
    </section>
</body>
</html>