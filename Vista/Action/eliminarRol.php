<?php
//ESTE SCRIPT ES EL ACTION PARA ELIMINAR/DESHABILITAR A UN ROL//
include_once('../../configuracion.php');

$datos = data_submitted(); //traigo los datos q entraron por post o get
$idrol = $datos['idrol']; //recupero el id que viene de datos
if (!$idrol) { //si no hay un id
    echo json_encode([
        'success' => false,//para el js 
        'msg' => 'ID de rol no proporcionado' //para el rol
    ]);
}else{ //si hay un id existente
     $ctrlRol = new RolController(); //hago un new de rol
    
    $fechaActual = date('Y-m-d H:i:s'); //tomo lafecha actual
    
    $param = [ //hago el param para eliminar/deshabilitar
        'idrol' => $idrol,//id delrol a eliminar
        'rodeshabilitado' => $fechaActual //fecha actual para deshabilitar
    ];
    
    $resultado = $ctrlRol->baja($param); //hago la baja que en realidad hace un borrado logico NO fisico
    
    if ($resultado) { //si se pudo deshabilitar
        echo json_encode([
            'success' => true, //clave para el js
            'msg' => 'Rol deshabilitado correctamente' //mensaje para el rol
        ]);
    } else { //si no se pudo deshabilitar
        echo json_encode([
            'success' => false, //para el js
            'msg' => 'No se pudo deshabilitar el rol' //para el rol
        ]);
    }
}

?>