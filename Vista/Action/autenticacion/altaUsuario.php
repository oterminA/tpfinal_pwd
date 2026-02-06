<?php
include_once '../../util/funciones.php';

$datos = data_submitted();

$controlUs = new UsuarioController();
$controlR  = new RolController();
$controlUR = new UsuarioRolController();

$usuario = trim($datos['usnombre'] ?? '');
$pass    = $datos['uspass'] ?? '';
$mail    = $datos['usmail'] ?? '';

if ($usuario === '') {
    header('Location: ../../../registrarse.php?error=datos');
    exit;
} else {
    $existeUsuario = $controlUs->buscar(['usnombre' => $usuario]);

    if (count($existeUsuario) > 0) {
        header('Location: ../../../registrarse.php?error=existe');
        exit;
    }

    $paramUser = [
        'usnombre'        => $usuario,
        'uspass'          => $pass,
        'usmail'          => $mail,
        'usdeshabilitado' => null
    ];

    $idUsuario = $controlUs->alta($paramUser);

    if (!$idUsuario) {
        header('Location: ../registrarse.php?error=alta');
        exit;
    } else {

        $roles = $controlR->buscar(['roldescripcion' => 'cliente']);

        if (count($roles) === 0) {
            header('Location: ../registrarse.php?error=rol');
            exit;
        } else {
            $idRol = $roles[0]->getIdRol();

            $paramUR = [
                'idusuario' => $idUsuario,
                'idrol'     => $idRol
            ];

            $controlUR->alta($paramUR);

            header('Location: ../login.php?msg=exito_registro');
            exit;
        }
    }
}
