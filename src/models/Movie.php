<?php
class Movie {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMovies() {
        $stmt = $this->pdo->query("SELECT * FROM Movie");
        return $stmt->fetchAll();
    }
}
?>
