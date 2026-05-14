<?php
require_once 'config.php';
session_start();

$sql = "SELECT purchases.activation_code, games.title
FROM purchases
JOIN games ON purchases.game_id = games.id
WHERE purchases.user_id = :user_id";
$prepared = $pdo->prepare($sql);
$prepared->execute(['user_id' => $_SESSION['user_id']]);
$purchases = $prepared->fetchAll();//para que muestre todas las compras del usuario


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EasyGames - Profile</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <a href="index.php">← Back to store</a>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>
    <p>Your balance: $<?php echo number_format($_SESSION['balance'], 2); ?></p>

    <!-- mostramos un mensaje de error si el codigo de recarga no es valido o ya se ha usado -->
    <!-- lo hacemos aqui mejor que en el api/recharge.php para que el mensaje se muestre en la pagina de perfil y no en una pagina en blanco -->

    <?php if (isset($_GET['error'])) { ?>
        <p>Invalid or already used code.</p>
    <?php } ?>

    <!-- añadimos la opcion de recarga de saldo -->
    <form method="POST" action="api/recharge.php">
        <input type="text" name="code" placeholder="Enter recharge code">
     <button type="submit">Recharge</button>
    </form>


    <!-- recorremos la lista de compras -->
    <h2>Your Purchases</h2>
    <table>
        <tr>
          <th>Game</th>
          <th>Activation Code</th>
        </tr>
        <?php foreach ($purchases as $purchase) { ?>
        <tr>
           <td><?php echo $purchase['title']; ?></td>
            <td><?php echo $purchase['activation_code']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>