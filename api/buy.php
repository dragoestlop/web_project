<?php

require_once '../config.php';
session_start();
$id = $_GET['id'];  //leemos la id del juego de la url que luego pasa a ser currentGameId en JS
// consultamos la bbdd para obtener el precio del juego
$sql = "SELECT price FROM games WHERE id = :id";
$prepared = $pdo->prepare($sql);
$prepared->execute(['id' => $id]);  
$game = $prepared->fetch();
$price = $game['price'];

// comprobamos que el usuario tiene suficiente saldo
if ($_SESSION['balance'] >= $price) {
    // restamos el precio al saldo del usuario
    $_SESSION['balance'] = round($_SESSION['balance'] - $price, 2);  //usamos round para que no haya problemas con los decimales
    // actualizamos el saldo en la bbdd
    $sql = "UPDATE users SET balance = :balance WHERE id = :user_id";  //actualiza el dato de balance de la tabla users del usuario con el que coincida la id
    $prepared = $pdo->prepare($sql);
    $prepared->execute(['balance' => $_SESSION['balance'], 'user_id' => $_SESSION['user_id']]);

    //devolvemoscódigo de activación aleatorio y guardanmos en la base de datos el usuario y juego comprado
    $activation_code = rand(10000000, 99999999);
    $sql = "INSERT INTO purchases (user_id, game_id, activation_code) VALUES (:user_id, :game_id, :activation_code)";
    $prepared = $pdo->prepare($sql);
    $prepared->execute([
    'user_id' => $_SESSION['user_id'],
    'game_id' => $id,
    'activation_code' => $activation_code
]);
// devolvemos el resultado del intento de compra en JSON para que lo lea JS
    echo json_encode(['success' => true, 'message' => 'Game purchased successfully', 'activation_code' => $activation_code, 'new_balance' => $_SESSION['balance']]);
} else {
    // si no tiene suficiente saldo devolvemos success false y sale un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Not enough balance']);
}

?>