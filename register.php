<?php
require_once 'config.php';
session_start();

// comprobamos si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // encriptamos la contraseña con funcion hash
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // guardamos user en bdd
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $prepared = $pdo->prepare($sql);
    $prepared->execute([
        'username' => $username,
        'email' => $email,
        'password' => $hash
    ]);

    // redirigimos al login
    header("Location: login.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EasyGames - Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- formulario igual que en index.php -->
    <form method="POST" action="register.php">
    <input type="text" name="username" placeholder="Username">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Register</button>
</form>
</body>
</html>