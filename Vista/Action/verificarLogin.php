<?php
//ESTE ACTION ES PARA VER SI LAS CREDENCIALES DEL USUARIO SON CORRECTAS, SE USA EN EL LOGIN//
include_once('../../configuracion.php');
$datos = data_submitted(); //recupero los datos que vienen por post o get
$usnombre = $datos['usnombre'];
$uspass = $datos['uspass'];

if (empty($usnombre) || empty($uspass)) { //si los campos están vacíos
    echo json_encode([
        'success' => false, //para js
        'msg' => 'Usuario y contraseña son obligatorios' //para el usuario
    ]);
} else {
    $objSession = new Session(); //hago un newde sesion para que se cree una sesion y despues guardar los datos necesarios para el carrito, las credenciales y demás
    $logueado = $objSession->iniciar($usnombre, $uspass); //guardo los datos de sesion del user y contraseña usando la funcion iniciar


    if ($logueado) { //si la sesion se validó entonces hago lo de guardar los roles en la sesion para despues poder hacer en home.php el tema de q el user que tenga +1 rol pueda cambiar de vista
        $ctrlUsuario = new UsuarioController(); //creo una instancia de usuario controller
        $listaUsuarios = $ctrlUsuario->buscar(['usnombre' => $usnombre]); //listo los usuarios con el user que necesito
        $objUsuario = $listaUsuarios[0];  //recupero un obj usuario que es el unico que hay porque verifiqué que no existan más de un usuario con el mismo nombre
    
        $ctrlUR = new UsuarioRolController(); //new de usuario rol
        $rolesDelUsuario = $ctrlUR->buscar(['idusuario' => $objUsuario->getIdUsuario()]); //listo los roles que estén asociados a un id usuario
    
        if (count($rolesDelUsuario) > 0) {//si el user tiene roles asignados
            $_SESSION['roles_poseidos'] = $rolesDelUsuario; //guardo en la superglobal los roles que tiene ese usuario
            $objRol = $rolesDelUsuario[0]->getObjRol(); //recupero un obj rol
            $_SESSION['rol_activo'] = $objRol->getIdRol(); //asigno el id de ese obj rol como rol activo de la session actual
            $_SESSION['rol_nombre_activo'] = $objRol->getRolDescripcion(); //lo mismo pero con el nombre de dicho rol
            $_SESSION['idusuario'] = $objUsuario->getIdUsuario(); //asigno también el id del usuario de esa session
        }

        echo json_encode([
            'success' => true, //´para el js
            'msg' => 'Inicio de sesión exitoso', //para el user
            'redirect' => 'Vista\paginas\home.php'  //redifirijo al home.php que es una pagina privada donde solo usuarios autenticados pueden entrar
        ]);
    } else { //si no se pudo iniciar la sesión
        echo json_encode([
            'success' => false, //para el js
            'msg' => 'Usuario/contraseña incorrectos o cuenta deshabilitada' //para el user, no pongo exactamente qué está mal porque sería peligroso para la seguirdad de la cuenta del usuario 
            //y tampoco redirijo
        ]);
    }
}

