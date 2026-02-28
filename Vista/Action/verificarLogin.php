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
}else{
    $objSession = new Session(); //hago un newde sesion para que se cree una sesion y despues guardar los datos necesarios para el carrito, las credenciales y demás
    $logueado = $objSession->iniciar($usnombre, $uspass); //guardo los datos de sesion del user y contraseña usando la funcion iniciar
    
    if ($logueado) { //si da true la variable quiere decir que funciona bien iniciar 
        echo json_encode([
            'success' => true, //´para el js
            'msg' => 'Inicio de sesión exitoso',//para el user
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
?>