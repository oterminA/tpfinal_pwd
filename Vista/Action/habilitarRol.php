<?php
//ESTE ACTION ES PARA HABILITAR A UN ROL DESPUÉS DE QUE FUE DESHABILITADO, no lo piden como tal perome parece importante tenerlo porque algo que se deshabilita puede habilitarse de nuevo//
include_once '../../configuracion.php' ;

$datos = data_submitted(); //traigo los datos que entran por post/get
$idrol = $datos['idrol']; //recupero el id del rol

if (!$idrol) { //si no hay ningun id
    echo json_encode([ //string php pasado a json para el ajax
        'success' => false, //para el js
        'msg' => 'ID de rol no proporcionado' //para el rol
    ]);
}else{ //si hay rol
      $objControl = new RolController(); //hago un new de la clase rol
    
    $param = [ //armo el parametro
        'idrol' => $idrol,
        'rodeshabilitado' => null // null = habilitado
    ];
    
    $resultado = $objControl->modificacion($param); //le paso ese parametro a moficiar para cambiar su estado
    
    if ($resultado) { //si dio true=se pudo modificar
        echo json_encode([
            'success' => true, //para el js
            'msg' => 'Rol habilitado correctamente' //para el rol
        ]);
    } else { //si nose pudo habiliar
        echo json_encode([
            'success' => false, //para js
            'msg' => 'No se pudo habilitar el rol' //para el rol
        ]);
    }
}
?>