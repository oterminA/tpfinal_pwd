<?php
include_once '../../configuracion.php';

$datos = data_submitted();
$controlMenu = new MenuController();
$controlMenuRol = new MenuRolController();

$param = [
    'idmenu' => $datos['idmenu'],
    'menombre' => $datos['menombre'],
    'medescripcion' => $datos['medescripcion'],
    'idpadre' => (!empty($datos['idpadre'])) ? $datos['idpadre'] : null,
    'medeshabilitado' => null 
];

$modificacion = $controlMenu->modificacion($param);

if ($modificacion) {
    
    $controlMenuRol->baja(['idmenu' => $datos['idmenu']]);
    
    if (isset($datos['roles']) && is_array($datos['roles'])) {
        foreach ($datos['roles'] as $idRol) {
            $paramMR = [
                'idmenu' => $datos['idmenu'],
                'idrol' => $idRol
            ];
            $controlMenuRol->alta($paramMR);
        }
    }

    echo json_encode(['success' => true, 'msg' => 'Menú y roles asociados actualizados correctamente']);
} else {
    echo json_encode(['success' => false, 'msg' => 'No se pudo actualizar el menú']);
}