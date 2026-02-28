<?php
include_once '../../configuracion.php';
$sesion = new Session(); //hago un new de session
$cerrar = $sesion->cerrar(); //llamo a la funcion cerrar uqe usa unset y destroy

?>