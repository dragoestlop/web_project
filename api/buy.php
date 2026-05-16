<?php

require_once '../config.php';
session_start();
$id = $_GET['id'];
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
    $sql = "UPDATE users SET balance = :balance WHERE id = :user_id";
    $prepared = $pdo->prepare($sql);
    $prepared->execute(['balance' => $_SESSION['balance'], 'user_id' => $_SESSION['user_id']]);

    // devolvemos un mensaje de éxito y un código de activación simulado
    $activation_code = strtoupper(bin2hex(random_bytes(5)));
    $sql = "INSERT INTO purchases (user_id, game_id, activation_code) VALUES (:user_id, :game_id, :activation_code)";
    $prepared = $pdo->prepare($sql);
    $prepared->execute([
    'user_id' => $_SESSION['user_id'],
    'game_id' => $id,
    'activation_code' => $activation_code
]);

    echo json_encode(['success' => true, 'message' => 'Game purchased successfully', 'activation_code' => $activation_code, 'new_balance' => $_SESSION['balance']]);
} else {
    // si no tiene suficiente saldo devolvemos un mensaje de error
    echo json_encode(['success' => false, 'message' => 'Not enough balance']);
}

?>