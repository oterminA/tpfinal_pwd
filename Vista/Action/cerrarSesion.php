<?php
include_once '../../util/funciones.php';

$sesion = new Session();
$cerrar = $sesion->cerrar();
header('Location: ../login.php');