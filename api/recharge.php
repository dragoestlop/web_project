<?php

require_once '../config.php';
session_start();
$code = $_POST['code'];

// consultamos la bbdd para verificar el código de recarga
$sql = "SELECT amount, used FROM recharge_codes WHERE code = :code"; 
$prepared = $pdo->prepare($sql);
$prepared->execute(['code' => $code]);
$result = $prepared->fetch();


if ($result && !$result['used']) { //comprueba que el codigo exista y no se haya usado
    // sumamos al user el saldo
    $amount = $result['amount'];
    $_SESSION['balance'] += $amount;

    // actualizamos el saldo en la bbdd
    $sql = "UPDATE users SET balance = :balance WHERE id = :user_id";
    $prepared = $pdo->prepare($sql);
    $prepared->execute(['balance' => $_SESSION['balance'], 'user_id' => $_SESSION['user_id']]);

    // actualizamos a used para q no se pueda usar el mismo código de recarga varias veces
    $sql = "UPDATE recharge_codes SET used = true WHERE code = :code";
    $prepared = $pdo->prepare($sql);
    $prepared->execute(['code' => $code]);

    header("Location: ../profile.php");
} else {
    // error msg
    header("Location: ../profile.php?error=invalid_code");
}

?>