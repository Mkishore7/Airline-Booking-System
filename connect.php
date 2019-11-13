<?php
date_default_timezone_set('Asia/Calcutta');
$host = 'localhost';
$db   = 'guesthouse';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

define("DB_HOST", $host);
define("DB_USER", $user);
define("DB_PASSWORD", $pass);
define("DB_NAME", $db);


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
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
