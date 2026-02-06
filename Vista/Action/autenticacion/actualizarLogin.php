<?php
include_once '../../util/funciones.php';

$datos = data_submitted();
$controlUs = new UsuarioController();

$param = [
    'idusuario' => $datos['idusuario'],
    'usnombre' => $datos['usnombre'],
    'usmail' => $datos['usmail'],
    'usdeshabilitado' => null
];

if (!empty($datos['uspass'])) {
    $param['uspass'] = $datos['uspass'];
}

$modificacion = $controlUs->modificacion($param);

if ($modificacion) {
    header('Location: ../vistaAdmin.php?msg=exito_actualizacion');
} else {
    header('Location: ../vistaAdmin.php?msg=error_datos');
}
exit;
