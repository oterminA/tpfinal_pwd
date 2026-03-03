<?php
include_once '../../configuracion.php';
$datos = data_submitted();
$sesion = new Session();

$idRolElegido = $datos['idrol'];
$listaRolesPoseidos = $_SESSION['roles_poseidos']; 

$tienePermiso = false;
foreach ($listaRolesPoseidos as $itemRol) {
    if ($itemRol->getObjRol()->getIdRol() == $idRolElegido) {
        $tienePermiso = true;
    }
}

if ($tienePermiso) {
    $_SESSION['rol_activo'] = $idRolElegido;
    foreach ($listaRolesPoseidos as $itemRol) {
        if ($itemRol->getObjRol()->getIdRol() == $idRolElegido) {
            $_SESSION['rol_nombre_activo'] = $itemRol->getObjRol()->getRolDescripcion();
        }
    }
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'msg' => 'No tienes permiso para este rol']);
}