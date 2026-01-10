<?php
include_once __DIR__ . '/../conector/BaseDatos.php';
include_once __DIR__ . '/../../Control/RolController.php';

$bd = new BaseDatos();
$ctrlRol = new RolController;

//alta
$paramRol = ['rodescripcion'=> 'admin'];
$altaRol = $ctrlRol->alta($paramRol);
if ($altaRol){
    echo "ALTA REALIZADA.\n";
}else{
    echo "ERROR.\n";
}


// //baja
// $paramBaja = ['idrol'=>0];
// $baja = $ctrlRol->baja($paramBaja);
// if ($baja){
//     echo "BAJA REALIZADA.\n";
// }else{
//     "ERROR.\n";
// }