
<?php
    include_once __DIR__ . '/../src/helpers/session_helper.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FilmQuest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="icon" href="images/tab_logo.png" type="image/x-icon">
    <style>
        .section1 {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 0 20px;
        }

        .form-box {
            max-width: 100%;
            width: 512px;
        }

        .login-container,
        .register-cont {
            text-align: center;
        }

        @media(max-width: 576px) {
            .form-box {
                width: 100%;
            }
        }

        .login-container header,
        .register-cont header {
            font-size: 36px;
            font-weight: bold;
            color: #934A5F;
            margin-bottom: 20px;
            font-family: 'Lucky Bones';
            text-shadow: -2px -2px 0 #e5e5e5, 2px -2px 0 #e5e5e5, -2px 2px 0 #e5e5e5, 2px 2px 0 #e5e5e5;
        }
    </style>
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
                    <li><a href="login.php" class="active">Login</a></li>
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
                <li><a href="login.php" class="active">Login</a></li>
            </ul>
        </div>
        <div class="form-box">
            <div class="login-container" id="login">
                <?php flash('login') ?>
                <form method="post" action="../src/controllers/Users.php">
                    <input type="hidden" name="type" value="login">
                    <div class="top">
                        <span>Don't have an account? <a href="#" onclick="toggleForm()">Sign Up</a></span>
                        <header>Login</header>
                    </div>
                    <div class="input-cont">
                        <input type="text" name="username_email" class="input-field" placeholder="Username or Email">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-cont">
                        <input type="password" name="pwd" class="input-field" placeholder="Password">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-cont">
                        <input type="submit" class="submit" value="Sign In">
                    </div>
                    <div class="two-col">
                        <div class="one">
                            <input type="checkbox" id="login-check" name="remember_me">
                            <label for="login-check"> Remember Me</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="register-cont" id="register" style="display: none;">
                <?php flash('register') ?>
                <form method="post" action="../src/controllers/Users.php">
                    <input type="hidden" name="type" value="register">
                    <div class="top">
                        <span>Have an account?<a href="#" onclick="toggleForm()">Login</a></span>
                        <header>Sign Up</header>
                    </div>
                    <div class="forms">
                        <div class="input-cont">
                            <input type="text" name="first_name" class="input-field" placeholder="First name">
                            <i class="bx bx-user"></i>
                        </div>
                        <div class="input-cont">
                            <input type="text" name="last_name" class="input-field" placeholder="Last name">
                            <i class="bx bx-user"></i>
                        </div>
                    </div>
                    <div class="input-cont">
                        <input type="text" name="email" class="input-field" placeholder="Email">
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="input-cont">
                        <input type="text" name="username" class="input-field" placeholder="Username">
                        <i class="bx bx-user"></i>
                    </div>
                    <div class="input-cont">
                        <input type="password" name="pwd" class="input-field" placeholder="Password">
                        <i class="bx bx-lock-alt"></i>
                    </div>
                    <div class="input-cont">
                        <input type="submit" class="submit" value="Sign up">
                    </div>
                </form>
            </div>
        </div>

        <script>
            function toggleForm() {
                var loginForm = document.getElementById('login');
                var registerForm = document.getElementById('register');

                loginForm.style.display = loginForm.style.display === 'none' ? 'block' : 'none';
                registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
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
            window.onload = function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('form') === 'register') {
                    document.getElementById('login').style.display = 'none';
                    document.getElementById('register').style.display = 'block';
                }
            };
        </script>
    </section>
</body>

</html>
