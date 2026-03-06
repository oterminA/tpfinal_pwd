<?php
//ESTE SCRIPT ES EL ACTION PARA ELIMINAR/DESHABILITAR A UN MENU//
include_once('../../configuracion.php');

$datos = data_submitted(); //traigo los datos q entraron por post o get
$idmenu = $datos['idmenu']; //recupero el id que viene de datos
if (!$idmenu) { //si no hay un id
    echo json_encode([
        'success' => false,//para el js 
        'msg' => 'ID de menu no proporcionado' //para el menu
    ]);
}else{ //si hay un id existente
     $ctrlMenu = new MenuController(); //hago un new de menu
    
    $fechaActual = date('Y-m-d H:i:s'); //tomo lafecha actual
    
    $param = [ //hago el param para eliminar/deshabilitar
        'idmenu' => $idmenu,//id delmenu a eliminar
        'medeshabilitado' => $fechaActual //fecha actual para deshabilitar
    ];

    if ($ctrlMenu->tieneHijos($idmenu)) { //ESTO ES para no desactivar menus que tengan hijos, todavía estoy debatiendo si debería poderse cuando los hijos estén desactivados pero por ahora me quedo con esa posición más tajante de que si hay hijos no desactivo
        echo json_encode(['success' => false, 'msg' => 'No se puede deshabilitar: este menú tiene submenús asociados.']);
    }else{
        $resultado = $ctrlMenu->baja($param); //hago la baja que en realidad hace un borrado logico NO fisico
    
        if ($resultado) { //si se pudo deshabilitar
            echo json_encode([
                'success' => true, //clave para el js
                'msg' => 'Menu deshabilitado correctamente' //mensaje para el menu
            ]);
        } else { //si no se pudo deshabilitar
            echo json_encode([
                'success' => false, //para el js
                'msg' => 'No se pudo deshabilitar el menu' //para el menu
            ]);
        }
    }
    }
?>