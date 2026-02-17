<?php
include_once('../../configuracion.php');

$datos = data_submitted();

$usnombre = trim($datos['usnombre'] ?? '');
$uspass = $datos['uspass'] ?? '';
$usmail = $datos['usmail'] ?? '';

if (empty($usnombre) || empty($uspass) || empty($usmail)) {
    echo json_encode([
        'success' => false,
        'msg' => 'Todos los campos son obligatorios'
    ]);
    exit;
}

try {
    $objControlUs = new UsuarioController();
    $objControlR = new RolController();
    $objControlUR = new UsuarioRolController();
    
    $existeUsuario = $objControlUs->buscar(['usnombre' => $usnombre]);
    
    if (count($existeUsuario) > 0) {
        echo json_encode([
            'success' => false,
            'msg' => 'El nombre de usuario ya existe'
        ]);
        exit;
    }
    
    $existeMail = $objControlUs->buscar(['usmail' => $usmail]);
    
    if (count($existeMail) > 0) {
        echo json_encode([
            'success' => false,
            'msg' => 'El email ya está registrado'
        ]);
        exit;
    }
    
    $paramUser = [
        'usnombre' => $usnombre,
        'uspass' => $passHash,
        'usmail' => $usmail,
        'usdeshabilitado' => null
    ];
    
    $resultado = $objControlUs->alta($paramUser);
    
    if ($resultado) {
        $usuariosCreados = $objControlUs->buscar(['usnombre' => $usnombre]);
        $nuevoUsuario = $usuariosCreados[0];
        $idUsuario = $nuevoUsuario->getIdUsuario();
        
        $roles = $objControlR->buscar(['roldescripcion' => 'cliente']);
        
        if (count($roles) > 0) {
            $idRol = $roles[0]->getIdRol();
            
            $paramUR = [
                'idusuario' => $idUsuario,
                'idrol' => $idRol
            ];
            
            $objControlUR->alta($paramUR);
            
            echo json_encode([
                'success' => true,
                'msg' => 'Usuario registrado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Error: Rol "cliente" no encontrado'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'msg' => 'Error al crear el usuario'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'msg' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>