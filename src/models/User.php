<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class User{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findUserByEmailOrUsername($email, $username){
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
        $stmt->execute(['email' => $email, 'username' => $username]);

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        return $row ? $row : false;
    }

    public function register($data){
        $stmt = $this->pdo->prepare('INSERT INTO users (first_name, last_name, email, username, pwd) VALUES(:first_name, :last_name, :email, :username, :pwd)');
        $stmt->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'pwd' => $data['pwd']
        ]);

        return $stmt->rowCount() > 0;
    }

    public function login($nameOrEmail, $pwd){
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email OR username = :username');
        $stmt->bindValue(':email', $nameOrEmail);
        $stmt->bindValue(':username', $nameOrEmail);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if($user && password_verify($pwd, $user->pwd)){
            return $user;
        } else {
            return false;
        }
    }
}

?>