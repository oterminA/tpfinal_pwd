<?php
include_once '../../util/funciones.php';


$datos = data_submitted(); 
$controlUs = new UsuarioController();

if (isset($datos['idusuario'])) {
    if ($controlUs->baja($datos)) { 
        header('Location: ../vistaAdmin.php?exito_baja');
    } else {
        header('Location: ../vistaAdmin.php?error=fallo_baja');
    }
}else {
    header('Location: ../vistaAdmin.php?error=fallo_id');
}