<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA//
//solo el admin tiene acceso a este script
include_once '../../configuracion.php';
$datos = data_submitted(); 

$objControlMenu = new MenuController(); //nueva instancia de control menu
$objControlMenuRol = new MenuRolController(); //new de menurol controller 

$existeMenu = $objControlMenu->buscar(['menombre' => $datos['menombre']]); //busco si el menu existe

if (count($existeMenu) > 0) { //si existe
    echo json_encode(['success' => false, 'msg' => 'El menú ya existe']);
} else {//si NO existe
    $paramMenu = [ //armo el parametro con los datos q necesito
        'menombre' => $datos['menombre'],
        'medescripcion' => $datos['medescripcion'],
        'idpadre' => (!empty($datos['idpadre'])) ? $datos['idpadre'] : null, //pongo esto así porque puede ser y de hecho pasa en mi proyecto que no tengo submenus por ende no hay menu padre
        'medeshabilitado' => null
    ];

    $respuestaAlta = $objControlMenu->alta($paramMenu);  //hago el alta

    if ($respuestaAlta) { //sise pudo crear el menu
        $listaMenus = $objControlMenu->buscar(['menombre' => $datos['menombre']]); //listo los menus con ese nombre
        $objMenuRecienCreado = $listaMenus[0]; //me quedo con el primero y enrealidad el unico que hay xq ya verifiqué que no se repita
        $idNuevoMenu = $objMenuRecienCreado->getIdMenu(); //agarro el id de ese menu

        if (isset($datos['roles']) && is_array($datos['roles'])) { //busco los roles que vienen desde el post
            foreach ($datos['roles'] as $idRol) { //itero sobre cada rol
                $paramMR = [ //hago el alta de menurol o sea de los menus que tiene un rol
                    'idmenu' => $idNuevoMenu, 
                    'idrol'  => $idRol
                ];
                $altaMenuRol= $objControlMenuRol->alta($paramMR);
                if ($altaMenuRol){
                    echo json_encode(['success' => true, 'msg' => 'Menú y roles asociados correctamente']);
                }else{
                    echo json_encode(['success' => true, 'msg' => 'Menú creado, pero hubo errores al asociar algunos roles']);
                }
            }
        }
    } else { //si no se pudo hacer el menu directamente
        echo json_encode(['success' => false, 'msg' => 'Error al crear el menú en la base de datos']);
    }
}