<?php
include_once '../../configuracion.php'; 
$sesion = new Session();
$idRol = $sesion->getRol(); 

$menuRolCtrl = new MenuRolController();
$lista = $menuRolCtrl->Buscar(['idrol' => $idRol]);

$resultado = [];
foreach ($lista as $objMR) {
    $objMenu = $objMR->getObjMenu();

    if ($objMenu->getDeshabilitado() == null || $objMenu->getDeshabilitado() == "0000-00-00 00:00:00") {
        $resultado[] = [
            'id' => $objMenu->getIdMenu(),
            'text' => $objMenu->getNombreMenu(),
            'url' => "paginas/home.php"
        ];
    }
}
echo json_encode($resultado);