<?php
include_once '../../util/funciones.php';

$bd = new BaseDatos();
$ctrlRol = new RolController();
$ctrlUsuario = new UsuarioController();


//alta
// $paramRol = ['rodescripcion'=> 'deposito'];
// $altaRol = $ctrlRol->alta($paramRol);
// if ($altaRol){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }

// $paramUsuario = ['usnombre'=> 'caro', 'uspass'=> 0123, 'usmail'=> 'caro@yahoo.com', 'usdeshabilitado'=> ''];
// $altaUsuario = $ctrlUsuario->alta($paramUsuario);
// if ($altaUsuario){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }


// //baja
// $paramBaja = ['idrol'=>0];
// $baja = $ctrlRol->baja($paramBaja);
// if ($baja){
//     echo "BAJA REALIZADA.\n";
// }else{
//     "ERROR.\n";
// }