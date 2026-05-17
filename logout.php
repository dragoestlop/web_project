<?php
require_once 'config.php';  //no se usa en este archivo realmente pero por consistencia
session_start();
session_destroy();  // destruimos la sesión y borramos todo
header("Location: index.php"); // redirigimos al index despues de cerrar la sesion
?>