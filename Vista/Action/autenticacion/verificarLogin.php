<?php
include_once '../../util/funciones.php';

$datos = data_submitted();
$user = $datos['usnombre'];
$pass = $datos['uspass'];

$sesion = new Session();

if ($sesion->iniciar($user, $pass)) {

    $rol = $sesion->getRol();

    if ($rol === 'admin') {
        header('Location: ../vistaAdmin.php');
    } elseif ($rol === 'cliente'){
        header('Location: ../listarUsuario.php');
    }

} else {
    header('Location: ../login.php?error');
}

?>