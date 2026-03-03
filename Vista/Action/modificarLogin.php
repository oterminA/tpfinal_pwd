<?php
include_once '../../configuracion.php';

$datos = data_submitted();
$controlUs = new UsuarioController();
$controlUR = new UsuarioRolController();

$listaUsuarios = $controlUs->buscar(['idusuario' => $datos['idusuario']]);
$objUsuario = $listaUsuarios[0];

$param = [
    'idusuario' => $datos['idusuario'],
    'usnombre' => $datos['usnombre'],
    'usmail' => $datos['usmail'],
    'usdeshabilitado' => null
];

if (!empty($datos['uspass'])) { //esto es por si la contra viene vacia porque es opcional
    $param['uspass'] = $datos['uspass'];
} else {
    $param['uspass'] = $objUsuario->getContrasenia(); //traigo la contra vieja si es q el user no pone nada
}

$modificacion = $controlUs->modificacion($param); //si se puede modificar

if ($modificacion) {
    $roles = $controlUR->buscar(['idusuario' => $datos['idusuario']]); //busco los roles que ese user tenga enla relacion de usuariorol por ejemplo para el usuario con id 12 se traen las filas con los roles que ese id tenfa
    foreach ($roles as $objUR) { //acá tomo un objeto usuario rol que tiene por id de la relación 'id' y elimino cada una de las tablas asociadas al usuario para modificarlas con las nuevas
        $paramURBaja = [
                'id' => $objUR->getId() 
            ];
        $controlUR->baja($paramURBaja);
    }

    if (isset($datos['roles']) && is_array($datos['roles'])) { //si hay roles con esos parametros
        foreach ($datos['roles'] as $idRol) { //hago alta otra vezf de cada rol por esot de que un usuario puede tener muchos roles y muchos roles pueden 'ser tenidos' por un usuario
            $paramUR = [
                'idusuario' => $datos['idusuario'],
                'idrol' => $idRol
            ];
            $controlUR->alta($paramUR);
        }
    }
    echo json_encode(['success' => true, 
    'msg' => 'Usuario y roles actualizados correctamente']);
} else {
    echo json_encode(['success' => false, 
    'msg' => 'No se pudo actualizar el usuario']);
}
