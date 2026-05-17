<?php
require_once 'config.php';
session_start();

//pedimos el campo activation_code de la tabla purchases y el title de la tabla games
$sql = "SELECT purchases.activation_code, games.title  
FROM purchases
JOIN games ON purchases.game_id = games.id
WHERE purchases.user_id = :user_id";  //accedemos a dos tablas a la vez (campo game_id de purchases que coincida con campo id de games)
$prepared = $pdo->prepare($sql);
$prepared->execute(['user_id' => $_SESSION['user_id']]);
$purchases = $prepared->fetchAll();//recoge los campos solicitados de los elementos de las dos tablas cuyo game_id coincida 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EasyGames - Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="profile-nav"> <!-- botones de atras y logout -->
    <a class="store-link" href="index.php">← Back to store</a>
    <a class="logout-link" href="logout.php">Logout</a>
    </div>
    <div class="profile-container">
        <!-- nombre y saldo del usuario -->
        <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
        <p>Your balance: <?php echo round($_SESSION['balance'], 2); ?>€</p>

        <!-- mensaje de error si el codigo de recarga no es valido -->
        <?php if (isset($_GET['error'])) { ?>
            <p class="error-msg">Invalid or already used code.</p>
        <?php } ?>

        <!-- formulario de recarga de saldo -->
        <form class="recharge-form" method="POST" action="api/recharge.php"> <!--recharge.php recibe el codigo y comprueba si es valido en la BDD-->
            <input type="text" name="code" placeholder="Enter recharge code">
            <button type="submit">Recharge</button>
        </form>

        <!-- tabla de compras del usuario -->
        <h2>Your Purchases</h2>
        <table class="purchases-table"> <!-- creamos una tabla-->
            <tr> <!-- creamos una fila -->
                <th>Game</th> <!-- th es table header que lo pone en negrita-->
                <th>Activation Code</th>
            </tr>
            <!-- recorremos el array purchases en el que buy.php añade un elemento por cada compra y a cada elemento lo llamamos purchase-->
            <?php foreach ($purchases as $purchase) { ?> 
            <tr>
                <!-- metemos los campos de title y activation code de cada elemento del array en la tabla-->
                <td><?php echo $purchase['title']; ?></td>
                <td><?php echo $purchase['activation_code']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>