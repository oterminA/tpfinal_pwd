<?php
include_once '../../configuracion.php';

$datos = data_submitted();
$controlUs = new UsuarioController();

$listaUsuarios = $controlUs->buscar(['idusuario' => $datos['idusuario']]);

if (count($listaUsuarios) > 0) {
    $objUsuario = $listaUsuarios[0];

    $param = [
        'idusuario'       => $datos['idusuario'],
        'usnombre'        => $datos['usnombre'],
        'usmail'          => $datos['usmail'],
        'usdeshabilitado' => null
    ];

    if (isset($datos['uspass']) && !empty($datos['uspass'])) {
        $param['uspass'] = $datos['uspass']; 
    } else {
        $param['uspass'] = $objUsuario->getContrasenia(); 
    }

    $modificacion = $controlUs->modificacion($param);
    if ($modificacion) {
        $sesion = new Session(); //esto es re importante porque se tiene que iniciar que crear la sesion para guardar esps datos y así mostrarlos directamente sin hacer logout y login para poder verlos
        $_SESSION['usnombre'] = $datos['usnombre']; 
        $_SESSION['usmail']   = $datos['usmail'];
    
        echo json_encode(
            ['success' => true, 
        'msg' => 'Datos actualizados correctamente']);
    }
} else {
    echo json_encode(
        ['success' => false, 
    'msg' => 'Usuario no encontrado']);
}