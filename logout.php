<?php
require_once 'config.php';
session_start();
session_destroy();
header("Location: index.php"); // redirigimos al index despues de cerrar la sesion
?>