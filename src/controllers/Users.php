<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/User.php';
require_once '../helpers/session_helper.php';
require_once '../../config/config.php';

class Users {
    private $userModel;
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->userModel = new User($pdo);
        $this->pdo = $pdo;
    }

    public function register(){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = [
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'email' => trim($_POST['email']),
            'username' => trim($_POST['username']),
            'pwd' => trim($_POST['pwd']),
            'is_admin' => 0 
        ];

        $errors = [];

        if(empty($data['first_name']) || empty($data['last_name']) ||empty($data['username']) || empty($data['email']) ||  empty($data['pwd'])){
            flash("register", "Please fill all the inputs");
            redirect(BASE_URL . 'public/login.php?form=register');
            //$errors[] = "Please fill all the inputs";
            return;
        }

        if(!preg_match("/^[a-zA-Z]*$/", $data['first_name']) || !preg_match("/^[a-zA-Z]*$/", $data['last_name'])){
            flash("register", "Invalid characters");
            redirect(BASE_URL . 'public/login.php?form=register');
            //$errors[] = "Invalid characters";
            return;
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            flash("register", "Invalid email");
            redirect(BASE_URL . 'public/login.php?form=register');
            //$errors[] = "Invalid email";
            return;
        }

        if(strlen($data['pwd']) < 6){
            flash("register", "Password must be at least 6 characters");
            redirect(BASE_URL . 'public/login.php?form=register');
            //$errors[] = "Password must be at least 6 characters";
            return;
        }

        if($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])){
            flash("register", "Email or username already taken");
            redirect(BASE_URL . 'public/login.php?form=register');
            //$errors[] = "Email or username already taken";
            return;
        }

        //hash password
        $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);

        if($this->userModel->register($data)){
            flash("login", "You are registered and can log in", 'form-message form-message-green');
            redirect(BASE_URL . 'public/login.php');
        }else{
            flash("register", "Something went wrong");
            redirect(BASE_URL . 'public/login.php?form=register');
        }
    }

    public function login(){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $data = [
            'username_email' => trim($_POST['username_email']),
            'pwd' => trim($_POST['pwd']),
            'remember_me' => isset($_POST['remember_me']) ? true : false
        ];

        if(empty($data['username_email']) || empty($data['pwd'])){
            flash("login", "Please fill all the inputs");
            redirect(BASE_URL . 'public/login.php');
            exit();
        }

        if($this->userModel->findUserByEmailOrUsername($data['username_email'], $data['username_email'])){
            $loggedInUser = $this->userModel->login($data['username_email'], $data['pwd']);
            if($loggedInUser){
                $this->createUserSession($loggedInUser, $data['remember_me']);
            }else{
                flash("login", "Password incorrect");
                redirect(BASE_URL . 'public/login.php');
            }  
        }else{
            flash("login", "User not found");
            redirect(BASE_URL . 'public/login.php');
        }
        
    }

    public function createUserSession($user, $rememberMe){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        $_SESSION['is_admin'] = $user->is_admin;

        if ($rememberMe) {
            $token = bin2hex(random_bytes(16));
            setcookie('remember_me', $token, time() + (48 * 60 * 60), '/');
            $stmt = $this->pdo->prepare('INSERT INTO user_tokens (user_id, token) VALUES(:user_id, :token) ON DUPLICATE KEY UPDATE token = :new_token');
            $stmt->execute(['user_id' => $user->id, 'token' => $token, 'new_token' => $token]);
        } else {
            setcookie('remember_me', '', time() - 3600, '/');
        }

        redirect(BASE_URL . 'public/home.php');
    }

    public function logout(){
        session_start();

        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $stmt = $this->pdo->prepare('DELETE FROM user_tokens WHERE token = :token');
            $stmt->execute(['token' => $token]);
            setcookie('remember_me', '', time() - 3600, '/');
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        session_destroy();
        redirect(BASE_URL . 'public/home.php'); 
    }
}
require_once '../../config/config.php';
$users = new Users($pdo);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    switch($_POST['type']){
        case 'register':
            $users->register();
            break;
        case 'login':
            $users->login();
            break;
        default:
            echo json_encode(['error' => 'Invalid action']);
    }
}else{
    switch($_GET['q']){
        case 'logout':
            $users->logout();
            break;
        default:
            redirect(BASE_URL . 'public/login.php');
            
    }
}

?>