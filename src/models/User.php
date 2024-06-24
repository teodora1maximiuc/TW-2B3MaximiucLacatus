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

    /*se introduce noul utiliator in baza de date */
    public function register($data){
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (first_name, last_name, email, username, pwd, is_admin) VALUES(:first_name, :last_name, :email, :username, :pwd, :is_admin)');
            $stmt->execute([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'pwd' => $data['pwd'],
                'is_admin' => $data['is_admin'] ?? 0
            ]);
    
            if ($stmt->rowCount() > 0) {
                return true;  
            } else {
                return false;
            }
        } catch(PDOException $e) {
            error_log('Registration Error: ' . $e->getMessage()); 
            return false;
        }
    }

    /*se verifica username si parola */
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