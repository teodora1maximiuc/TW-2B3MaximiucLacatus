<?php
define('BASE_URL', 'http://localhost/TW-2B3MaximiucLacatus/');
$host = 'sql7.freesqldatabase.com';
$db = 'sql7714801';
$user = 'sql7714801';
$pass = 'Rp7WnGbdeH';

$dsn = "mysql:host=$host;dbname=$db";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
