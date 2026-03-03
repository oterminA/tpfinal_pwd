<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA//
//este script lo ve el admin solamente 
include_once '../../configuracion.php';
$datos = data_submitted(); //traigo los datos que entran por post o get

$usnombre = trim($datos['usnombre']);
$uspass = $datos['uspass'];
$usmail = $datos['usmail'];
$rol = isset($datos['idrol']) ? $datos['idrol'] : null; 
$rolesSeleccionados = isset($datos['roles']) ? $datos['roles'] : []; //acá recibo el rol o roles que puede venir desde el admin y dejo ese array vacio xq puede venir vacío porque en el registro normal el usuario no elige su rol
$objControlUs = new UsuarioController(); //new de usuario
$objControlR = new RolController(); //new de rol
$objControlUR = new UsuarioRolController(); //new de usuariorol

$existeUsuario = $objControlUs->buscar(['usnombre' => $usnombre]); //busco si el user existe porque noo puede duplicarse

if (count($existeUsuario) > 0) { //si existe
    echo json_encode([ //php lo hago json para que se pueda mostrar el msj en la funcion js
        'success' => false, //boolean para js
        'msg' => 'El nombre de usuario ya existe' //msj para el user
    ]);
} else { //si NO existe
    $paramUser = [ //armo el parametro para dar de alta al usuario
        'usnombre' => $usnombre,
        'uspass' => $uspass,
        'usmail' => $usmail,
        'usdeshabilitado' => null
    ];

    $resultado = $objControlUs->alta($paramUser); //hago el alta

    if ($resultado) { //si se pudo hacer el alta
        $usuariosCreados = $objControlUs->buscar(['usnombre' => $usnombre]); //listo los usuarios
        $nuevoUsuario = $usuariosCreados[0]; //acá me quedo con la primera coincidencia q aparezcaporqeu antes validé que no existiera un user con ese nombre
        $idUsuario = $nuevoUsuario->getIdUsuario(); //agarro el id de ese usuario

        if (!empty($rolesSeleccionados) && is_array($rolesSeleccionados)) { //si el array de roles no esta vacio
            foreach ($rolesSeleccionados as $idRol) { //recorro el array para hacer los altas de los roles
                $objControlUR->alta([ //hago el alta de usuariorol
                    'idusuario' => $idUsuario,
                    'idrol' => $idRol
                ]);
            }
        }else{
             $rolDefecto = $objControlR->buscar(['roldescripcion' => 'cliente']); //si se esta generando un usuario desde el registro del login pongoo un rol por defecto
            if (count($rolDefecto) > 0) { //si hay un rol
                $idRolDefecto = $rolDefecto[0]->getIdRol(); //recupero el id de ese rol
                $objControlUR->alta([ //hago el alta
                    'idusuario' => $idUsuario,
                    'idrol' => $idRolDefecto
                ]);
            }
        }
        echo json_encode(['success' => true, 'msg' => 'Usuario registrado exitosamente con sus roles']);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Error al crear el usuario']);
    }
}