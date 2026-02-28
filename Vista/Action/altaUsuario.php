<?php
//SCRIPT DONDE VOY A DAR DE ALTA A LOS USUARIOS//
include_once('../../configuracion.php');

$datos = data_submitted(); //traigo los datos que entran x post o get

$usnombre = trim($datos['usnombre']);
$uspass = $datos['uspass'];
$usmail = $datos['usmail'];

if (empty($usnombre) || empty($uspass) || empty($usmail)) { //valido que no estén vacíos los campos y devuelvo la respuesta en json porque estoy trabaajando con ajax y ajax usa json
    echo json_encode([
        'success' => false, //esta es una clave que le habla al js si la operacion salio bien o mal
        'msg' => 'Todos los campos son obligatorios' //esta es otra clave pero para que el usuario vea el mensaje, json trabaja con clave-valor
    ]);
} else {
        $objControlUs = new UsuarioController();
        $objControlR = new RolController();
        $objControlUR = new UsuarioRolController();

        $existeUsuario = $objControlUs->buscar(['usnombre' => $usnombre]); //busco al usuario

        if (count($existeUsuario) > 0) { //si hay algo en ese array significa que ya existe un user con esos datos y no puede pasar
            echo json_encode([ //mensaje php hecho json por el ajax
                'success' => false, //clave valor
                'msg' => 'El nombre de usuario ya existe'
            ]);
        }else{
            $paramUser = [ //armo el parametro de los datos del user para hacer el alta porque no hay duplicados del usuario
                'usnombre' => $usnombre,
                'uspass' => $uspass,
                'usmail' => $usmail,
                'usdeshabilitado' => null
            ];
    
            $resultado = $objControlUs->alta($paramUser); //hago el alta
    
            if ($resultado) { //si es true=se hizo el alta
                $usuariosCreados = $objControlUs->buscar(['usnombre' => $usnombre]); //busco el usuario
                $nuevoUsuario = $usuariosCreados[0]; //a la primera coincidencia lo obtengo
                $idUsuario = $nuevoUsuario->getIdUsuario(); //recupero el id de ese usuario que se creó
    
                $roles = $objControlR->buscar(['roldescripcion' => 'admin']); //busco el rol que le quiero dar
    
                if (count($roles) > 0) {//si existe ese rol
                    $idRol = $roles[0]->getIdRol(); //obtengo el id de ese rol por ejemplo cliente id 1
    
                    $paramUR = [ //armo el parametro para dar el alta con los datos que necesito
                        'idusuario' => $idUsuario, //iddel user recien creado
                        'idrol' => $idRol //id del rol que necesito
                    ];
    
                    $objControlUR->alta($paramUR); //algo el alta de usuario-rol, o sea cada user tiene un rol(admin, cliente, deposito)
    
                    echo json_encode([ //armo el string php padsado a json para mostrarlo si todo salió bien
                        'success' => true,
                        'msg' => 'Usuario registrado exitosamente'
                    ]);
                } else { //si no existe el rol que busco
                    echo json_encode([ //hago el string con el msj que quiero mostrar diciendo q no hay ese rol
                        'success' => false,
                        'msg' => 'Error: Rol no encontrado'
                    ]);
                }
            } else { //si no se pudo hacer el alta muestro el php json con el msj
                echo json_encode([
                    'success' => false, //para js, es un boolean
                    'msg' => 'Error al crear el usuario' //para el user
                ]);
            }
        }

        
}
