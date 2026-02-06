<?php
include_once '../../util/funciones.php';

$datos = data_submitted();
$controlUs = new UsuarioController();

$param = [
    'idusuario'       => $datos['idusuario'], 
    'usdeshabilitado' => null
];

$modificacion = $controlUs->modificacion($param);

if ($modificacion) {
    header('Location: ../vistaAdmin.php?msg=exito_habilitacion');
} else {
    header('Location: ../vistaAdmin.php?msg=error_datos');
}
exit;