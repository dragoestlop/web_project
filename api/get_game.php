<?php
require_once '../config.php';

// recogemos el id que nos manda el fetch de javascript, viene en la url
$id = $_GET['id'];

// consultamos la bbdd y scamos el juego con ese id
$sql = "SELECT * FROM games WHERE id = :id";
$prepared = $pdo->prepare($sql);
$prepared->execute(['id' => $id]);
$game = $prepared->fetch();

// devolvemos el resultado en formato JSON, que es el "idioma en común" en el que se entienden PHP y JavaScript
echo json_encode($game);
?>