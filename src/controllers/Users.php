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
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'email' => trim($_POST['email']),
            'username' => trim($_POST['username']),
            'pwd' => trim($_POST['pwd'])
        ];
        if(empty($data['first_name']) || empty($data['last_name']) ||empty($data['username']) || empty($data['email']) ||  empty($data['pwd'])){
            flash("register", "Please fill all the inputs");
            redirect(BASE_URL . 'public/login.php');
        }

        if(!preg_match("/^[a-zA-Z]*$/", $data['first_name']) || !preg_match("/^[a-zA-Z]*$/", $data['last_name'])){
            flash("register", "Invalid characters");
            redirect(BASE_URL . 'public/login.php');
        }

        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
            flash("register", "Invalid email");
            redirect(BASE_URL . 'public/login.php');
        }

        if(strlen($data['pwd']) < 6){
            flash("register", "Password must be at least 6 characters");
            redirect(BASE_URL . 'public/login.php');
        }

        if($this->userModel->findUserByEmailOrUsername($data['email'], $data['username'])){
            flash("register", "Email or username already taken");
            redirect(BASE_URL . 'public/login.php');
        }

        //hash password
        $data['pwd'] = password_hash($data['pwd'], PASSWORD_DEFAULT);

        if($this->userModel->register($data)){
            flash("register", "You are registered and can log in");
            redirect(BASE_URL . 'public/login.php');
        }else{
            flash("register", "Something went wrong");
            redirect(BASE_URL . 'public/login.php');
        }
    }

    public function login(){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'username_email' => trim($_POST['username_email']),
            'pwd' => trim($_POST['pwd'])
        ];

        if(empty($data['username_email']) || empty($data['pwd'])){
            flash("login", "Please fill all the inputs");
            redirect(BASE_URL . 'public/login.php');
            //header("location: ../../public/login.php");
            exit();
        }

        if($this->userModel->findUserByEmailOrUsername($data['username_email'], $data['username_email'])){
            $loggedInUser = $this->userModel->login($data['username_email'], $data['pwd']);
            if($loggedInUser){
                $this->createUserSession($loggedInUser);
            }else{
                flash("login", "Password incorrect");
                redirect(BASE_URL . 'public/login.php');
            }  
        }else{
            flash("login", "User not found");
            redirect(BASE_URL . 'public/login.php');
        }
        
    }

    public function createUserSession($user){
        session_start();
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['email'] = $user->email;
        //redirect('../../public/explore.php');
        redirect(BASE_URL . 'public/explore.php');
    }

    public function logout(){
        session_start();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        session_destroy();
        redirect(BASE_URL . 'public/login.php'); //index.php
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
            redirect('../../public/login.php'); //index.php
    }
}

?>