<?php
require_once 'config.php';
session_start();  //aunque no usemos $SESSION aquí la mantenemos activa por si el usuario ya se hubiera logueado (best practice)

// comprobamos si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // encriptamos la contraseña con funcion hash
    $hash = password_hash($password, PASSWORD_DEFAULT);  //guardamos la contraseña hasheada

    // guardamos user en bdd
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $prepared = $pdo->prepare($sql);

    try {
        $prepared->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hash
        ]);
        // si todo va bien redirigimos al login
        header("Location: login.php");
    } catch (PDOException $error_mail_user) {  //el error salta porque en la BDD las columnas son UNIQUE NOT NULL (no se pueden repetir)
        // si el username o email ya existen guardamos el mensaje de error
        $mensaje_error = "Username or email already registered";
    }
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
    <a class="back-link" href="index.php">← Back to store</a>
    <div class="form-container">
        <div class="form-box">
            <!-- logo que lleva a la tienda -->
            <a href="index.php"><img src="img/logo.png" alt="EasyGames"></a>
            <!-- mensaje de error si el usuario o email ya existen -->
            <?php if (isset($mensaje_error)) { ?>
                <p class="error"><?php echo $mensaje_error; ?></p>
            <?php } ?>
            <form method="POST" action="register.php">
                <input type="text" name="username" placeholder="Username">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <button type="submit">Register</button>
            </form>
            <!-- enlace al login -->
            <p class="form-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>