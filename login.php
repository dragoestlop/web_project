<?php
require_once 'config.php';
session_start();

//si el usuario rellena los campos y los envia (POST) los almacenamos en email y password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // buscamos el email
    $sql = "SELECT * FROM users WHERE email = :email";  //:email" se sustituye con el mail que ha metido el usuario
    $prepared = $pdo->prepare($sql);
    $prepared->execute(['email' => $email]);
    $user = $prepared->fetch();

    // comprobamos que la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) {
        // guardamos los datos en sesion
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['balance'] = $user['balance'];
        header("Location: index.php");
    } else {
        $error = "Email o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EasyGames - Login</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <a href="index.php">← Back to store</a>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>
</body>
</html>