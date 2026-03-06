<?php
//ESTE ACTION ES PARA HABILITAR A UN MENU DESPUÉS DE QUE FUE DESHABILITADO, no lo piden como tal perome parece importante tenerlo porque algo que se deshabilita puede habilitarse de nuevo//
include_once '../../configuracion.php' ;

$datos = data_submitted(); //traigo los datos que entran por post/get
$idmenu = $datos['idmenu']; //recupero el id del menu

if (!$idmenu) { //si no hay ningun id
    echo json_encode([ //string php pasado a json para el ajax
        'success' => false, //para el js
        'msg' => 'ID de menu no proporcionado' //para el menu
    ]);
}else{ //si hay menu
      $objMenu = new MenuController(); //hago un new de la clase menu
    
    $param = [ //armo el parametro
        'idmenu' => $idmenu,
        'medeshabilitado' => NULL // null = habilitado
    ];
    
    $resultado = $objMenu->modificacion($param); //le paso ese parametro a moficiar para cambiar su estado
    
    if ($resultado) { //si dio true=se pudo modificar
        echo json_encode([
            'success' => true, //para el js
            'msg' => 'Menu habilitado correctamente' //para el menu
        ]);
    } else { //si nose pudo habiliar
        echo json_encode([
            'success' => false, //para js
            'msg' => 'No se pudo habilitar el menu' //para el menu
        ]);
    }
}
?>