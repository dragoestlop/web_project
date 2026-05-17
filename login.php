<?php
require_once 'config.php';
session_start();

//si el usuario rellena los campos y los envia (POST) los almacenamos en $email y $password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // buscamos el email
    $sql = "SELECT * FROM users WHERE email = :email";  //:email" se sustituye con el mail que ha metido el usuario
    $prepared = $pdo->prepare($sql);  //$prepared es la consulta preparadapara evitar inyecciones SQL (con espacios en blanco)
    $prepared->execute(['email' => $email]); //se rellena el espacio en blanco de :email con el contenido de $email
    $user = $prepared->fetch();  //fetch pasa los datos de la base de datos al PHP

    // comprobamos que la contraseña es correcta
    if ($user && password_verify($password, $user['password'])) { //compara $password que acaban de escribir con la password hasheada en la BD
        // guardamos los datos en sesion
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['balance'] = $user['balance'];
        header("Location: index.php"); //una vez hehco el login te redirige a la pagina principal
    } else {
        $error = "Incorrect email or password.";
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
    <a class="back-link" href="index.php">← Back to store</a>
    <div class="form-container">
        <div class="form-box">
            <!-- logo que lleva a la tienda -->
            <a href="index.php"><img src="img/logo.png" alt="EasyGames"></a>
            <!-- mensaje de error si el login falla -->
            <?php if (isset($error)) { ?>
                <p class="error"><?php echo $error; ?></p>
            <?php } ?>
            <form method="POST" action="login.php">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Login</button>
            </form>
            <!-- enlace al registro -->
            <p class="form-link">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>