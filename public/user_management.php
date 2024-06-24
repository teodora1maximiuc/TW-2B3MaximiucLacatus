
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
            <h1>User Management</h1>
        </div>
        <div class="container">
            
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td id="permissions-cell-<?php echo $user['id']; ?>"><?php echo $user['is_admin'] ? 'Admin' : 'User'; ?></td>
                            <td>
                                <a href="../src/views/remove_user.php?id=<?php echo $user['id']; ?>" class="button">Remove</a>
                                <a href="javascript:void(0);" onclick="changePermissions(<?php echo $user['id']; ?>)" id="change-permissions-button-<?php echo $user['id']; ?>" class="button">
                                        <?php echo $user['is_admin'] ? 'Revoke Admin' : 'Grant Admin'; ?>
                                </a>
                                <a href="view_watchlist.php?id=<?php echo $user['id']; ?>" class="button">View Watchlist</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

        <script>
            /*ajax request */
            function changePermissions(userId) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "../src/views/change_permissions.php?id=" + userId, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            //alert('Permissions changed successfully');
                            var permissionsCell = document.getElementById('permissions-cell-' + userId);
                            permissionsCell.innerHTML = response.newPermission ? 'Admin' : 'User';
                            // Optionally update the button text if needed
                            var button = document.getElementById('change-permissions-button-' + userId);
                            button.innerHTML = response.newPermission ? 'Revoke Admin' : 'Grant Admin';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                };
                xhr.send();
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
