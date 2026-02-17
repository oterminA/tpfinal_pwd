<?php
session_start();
include_once('../../configuracion.php');

$datos = data_submitted();
$usnombre = $datos['usnombre'] ?? '';  
$uspass = $datos['uspass'] ?? '';  

if (empty($usnombre) || empty($uspass)) {
    echo json_encode([
        'success' => false, 
        'msg' => 'Usuario y contraseña son obligatorios'
    ]);
    exit;
}

try {
    $objSession = new Session();
    $logueado = $objSession->iniciar($usnombre, $uspass);
    
    if ($logueado) {
        echo json_encode([
            'success' => true, 
            'msg' => 'Login exitoso',
            'redirect' => 'Vista\paginas\home.php' 
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'msg' => 'Usuario/Contraseña incorrectos o cuenta deshabilitada'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'msg' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>