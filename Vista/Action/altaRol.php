<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA//
//este script lo ve el admin solamente 
include_once '../../configuracion.php';
$datos = data_submitted(); //traigo los datos que entran por post o get

$roldescripcion = trim($datos['roldescripcion']); //traigo la descripcion
$objControlR = new RolController(); //new de rol

$existeRol = $objControlR->buscar(['roldescripcion' => $roldescripcion]); //busco si el rol existe porque noo puede duplicarse

if (count($existeRol) > 0) { //si existe
    echo json_encode([ //php lo hago json para que se pueda mostrar el msj en la funcion js
        'success' => false, //boolean para js
        'msg' => 'El rol ya existe' //msj para el rol
    ]);
} else { //si NO existe
    $paramRol = [ //armo el parametro para dar de alta al rol
        'roldescripcion' => $roldescripcion,
    ];

    $resultado = $objControlR->alta($paramRol); //hago el alta

    if ($resultado) { //si se pudo hacer el alta
        echo json_encode(['success' => true, 'msg' => 'Rol creado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Error al crear el rol']);
    }
}