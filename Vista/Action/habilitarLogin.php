<?php
include_once('../../configuracion.php');

$datos = data_submitted();
$idusuario = $datos['idusuario'] ?? null;

if (!$idusuario) {
    echo json_encode([
        'success' => false,
        'msg' => 'ID de usuario no proporcionado'
    ]);
    exit;
}

try {
    $objControl = new UsuarioController();
    
    $param = [
        'idusuario' => $idusuario,
        'usdeshabilitado' => null // null = habilitado
    ];
    
    $resultado = $objControl->modificacion($param);
    
    if ($resultado) {
        echo json_encode([
            'success' => true,
            'msg' => 'Usuario habilitado correctamente'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'msg' => 'No se pudo habilitar el usuario'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'msg' => 'Error del servidor: ' . $e->getMessage()
    ]);
}
?>