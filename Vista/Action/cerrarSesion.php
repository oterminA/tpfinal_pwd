<?php
session_start();

$_SESSION = array();

$cerrar = $sesion->cerrar();

header('Location: ../login/login.php');
exit;
?>