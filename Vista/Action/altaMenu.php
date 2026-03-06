<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA//
//solo el admin tiene acceso a este script
include_once '../../configuracion.php';
$datos = data_submitted(); 

$objControlMenu = new MenuController();
$objControlMenuRol = new MenuRolController(); 

$existeMenu = $objControlMenu->buscar(['menombre' => $datos['menombre']]);

if (count($existeMenu) > 0) {
    echo json_encode(['success' => false, 'msg' => 'El menú ya existe']);
} else {
    $paramMenu = [
        'menombre' => $datos['menombre'],
        'medescripcion' => $datos['medescripcion'],
        'idpadre' => (!empty($datos['idpadre'])) ? $datos['idpadre'] : null,
        'medeshabilitado' => null
    ];

    $respuestaAlta = $objControlMenu->alta($paramMenu); 

    if ($respuestaAlta) {
        $listaMenus = $objControlMenu->buscar(['menombre' => $datos['menombre']]);
        $objMenuRecienCreado = $listaMenus[0];
        $idNuevoMenu = $objMenuRecienCreado->getIdMenu();

        $errorRoles = false;
        if (isset($datos['roles']) && is_array($datos['roles'])) {
            foreach ($datos['roles'] as $idRol) {
                $paramMR = [
                    'idmenu' => $idNuevoMenu,
                    'idrol'  => $idRol
                ];
                if (!$objControlMenuRol->alta($paramMR)) {
                    $errorRoles = true;
                }
            }
        }

        if (!$errorRoles) {
            echo json_encode(['success' => true, 'msg' => 'Menú y roles asociados correctamente']);
        } else {
            echo json_encode(['success' => true, 'msg' => 'Menú creado, pero hubo errores al asociar algunos roles']);
        }
    } else {
        echo json_encode(['success' => false, 'msg' => 'Error al crear el menú en la base de datos']);
    }
}