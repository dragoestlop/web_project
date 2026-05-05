<?php
// conexion a la base de datos, basada en la diapositiva 54 de PHP
$host = "localhost";
$port = "5432";
$db   = "videogame_store";
$user = "postgres";
$pass = "admin"; // la contraseña que he puesto en postgresql

$dsn = "pgsql:host=$host;port=$port;dbname=$db;"; //esta linea dice a que base de datos conectarse, se sustituyen los $por los que hemos puesto arriba

// usamos PDO para conectarnos, si falla muestra el error
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}
?>