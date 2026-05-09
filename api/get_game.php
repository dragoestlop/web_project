<?php
require_once '../config.php';

// recogemos el id que nos manda el fetch de javascript
$id = $_GET['id'];

// consultamos la bbdd
$sql = "SELECT * FROM games WHERE id = :id";
$prepared = $pdo->prepare($sql);
$prepared->execute(['id' => $id]);
$game = $prepared->fetch();

// devolvemos el resultado en formato JSON, no HTML
header('Content-Type: application/json');
echo json_encode($game);
?>