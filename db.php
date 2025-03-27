<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "finance_db";
$port = 3306;

try {
    $GLOBALS['pdo'] = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $GLOBALS['pdo']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>