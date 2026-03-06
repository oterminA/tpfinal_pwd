<?php
include_once '../../configuracion.php';

$datos = data_submitted();
$controlRol = new RolController();

$param = [
    'idrol' => $datos['idrol'],
    'roldescripcion' => $datos['roldescripcion'],
    'rodeshabilitado' => null
];


$modificacion = $controlRol->modificacion($param); //si se puede modificar

if ($modificacion) {
    echo json_encode(['success' => true, 
    'msg' => 'Rol actualizado correctamente']);
} else {
    echo json_encode(['success' => false, 
    'msg' => 'No se pudo actualizar el rol']);
}
