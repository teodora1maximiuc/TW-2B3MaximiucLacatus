
<?php
    session_start();
    include_once __DIR__ . '/../src/helpers/session_helper.php';
    include_once __DIR__ . '/../config/config.php';
    $query = "SELECT id, first_name, last_name, username, email, is_admin FROM users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/user_management.css">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="user_management.php" class="active">User Management</a></li>
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
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="help.php">Help</a></li>
                    <li><a href="user_management.php" class="active">User Management</a></li>
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
            <div>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
        <script>
            const ctx = document.getElementById('myChart');
            new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                borderWidth: 1
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
