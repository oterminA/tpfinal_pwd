<?php
//ESTE SCRIPT ES EL ACTION PARA ELIMINAR/DESHABILITAR A UN USER, LO HICE EN SU MOMENTO Y QUEDÓ ASÍ PERO CON LOS CAMBIOS NUEVOS NO SÉ SI ESTÁ ACTUALZIADO//
include_once('../../configuracion.php');

$datos = data_submitted(); //traigo los datos q entraron por post o get
$idusuario = $datos['idusuario']; //recupero el id que viene de datos

if (!$idusuario) { //si no hay un id
    echo json_encode([
        'success' => false,//para el js 
        'msg' => 'ID de usuario no proporcionado' //para el user
    ]);
}else{ //si hay un id existente
     $objControl = new UsuarioController(); //hago un new de usuario
    
    // $fechaActual = date('Y-m-d H:i:s'); //tomo lafecha actual
    
    $param = [ //hago el param para eliminar/deshabilitar
        'idusuario' => $idusuario,//id deluser a eliminar
        // 'usdeshabilitado' => $fechaActual //fecha actual para deshabilitar
    ];
    
    $resultado = $objControl->baja($param); //hago la baja que en realidad hace un borrado logico NO fisico
    
    if ($resultado) { //si se pudo deshabilitar
        echo json_encode([
            'success' => true, //clave para el js
            'msg' => 'Usuario deshabilitado correctamente' //mensaje para el user
        ]);
    } else { //si no se pudo deshabilitar
        echo json_encode([
            'success' => false, //para el js
            'msg' => 'No se pudo deshabilitar el usuario' //para el user
        ]);
    }
}

?>