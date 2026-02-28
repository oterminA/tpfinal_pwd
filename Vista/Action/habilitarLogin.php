<?php
//ESTE ACTION ES PARA HABILITAR A UN USUARIO DESPUÉS DE QUE FUE DESHABILITADO, no lo piden como tal perome parece importante tenerlo porque algo que se deshabilita puede habilitarse de nuevo//
include_once '../../configuracion.php' ;

$datos = data_submitted(); //traigo los datos que entran por post/get
$idusuario = $datos['idusuario']; //recupero el id del usuario

if (!$idusuario) { //si no hay ningun id
    echo json_encode([ //string php pasado a json para el ajax
        'success' => false, //para el js
        'msg' => 'ID de usuario no proporcionado' //para el user
    ]);
}else{ //si hay user
      $objControl = new UsuarioController(); //hago un new de la clase usuario
    
    $param = [ //armo el parametro
        'idusuario' => $idusuario,
        'usdeshabilitado' => null // null = habilitado
    ];
    
    $resultado = $objControl->modificacion($param); //le paso ese parametro a moficiar para cambiar su estado
    
    if ($resultado) { //si dio true=se pudo modificar
        echo json_encode([
            'success' => true, //para el js
            'msg' => 'Usuario habilitado correctamente' //para el usuario
        ]);
    } else { //si nose pudo habiliar
        echo json_encode([
            'success' => false, //para js
            'msg' => 'No se pudo habilitar el usuario' //para el user
        ]);
    }
}
?>