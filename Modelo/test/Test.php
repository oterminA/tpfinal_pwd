<?php
include_once '../../util/funciones.php';

$bd = new BaseDatos();
$ctrlRol = new RolController();
$ctrlProducto = new ProductoController();
$ctrlUsuario = new UsuarioController();
$ctrlUsuarioRol = new UsuarioRolController();
$ctrlMenu = new MenuController();
$ctrlMenuRol = new MenuRolController();
$ctrlCompra = new CompraController();
$ctrlCompraEstado = new CompraEstadoController();
$ctrlCompraEstadoTipo = new CompraEstadoTipoController();
$ctrlCompraItem = new CompraItemController();



////////////////////////////////////////////////////////alta
// $paramRol = ['roldescripcion'=> 'admin'];
// $altaRol = $ctrlRol->alta($paramRol);
// if ($altaRol){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }

// $paramUsuario = ['usnombre'=> 'caro', 'uspass'=> "0123", 'usmail'=> 'caro@admin.com', 'usdeshabilitado'=> null];
// $altaUsuario = $ctrlUsuario->alta($paramUsuario);
// if ($altaUsuario){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }

// $paramUsuarioRol = ['idrol'=> 3, 'idusuario'=> 3];
// $altaUsuarioRol = $ctrlUsuarioRol->alta($paramUsuarioRol);
// if ($altaUsuarioRol){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }

// 1cliente
// 2deposito
// 3admin

// 1Productos
// 2Inicio
// 3Contacto
// 4Ingresar
// 5Registrar
// 6Mi cuenta
// 7Carrito
// 8Salir
// 9Usuarios
// 10Roles
//11 menu

// $paramMenuRol = ['idmenu'=> 8, 'idrol'=> 3];
// $altaMenuRol = $ctrlMenuRol->alta($paramMenuRol);
// if ($altaMenuRol){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }


// $paramMenu = ['menombre' => 'Menu', 'medescripcion' => 'para hacer modificaciones a las opciones de menu', 'idpadre' => null, 'medeshabilitado' => null];
// // var_dump($paramMenu);
// $altaMenu = $ctrlMenu->alta($paramMenu);
// if ($altaMenu) {
//     echo "ALTA REALIZADA.\n";
// } else {
//     echo "ERROR EN EL ALTA.\n";
// }

// $paramProducto = ['pronombre'=> 'Arenero', 'prodetalle'=> 'arenero grande para gatos', 'procantstock'=> 15, 'proprecio'=> 50000, 'prodeshabilitado'=> null];
// $altaProducto = $ctrlProducto->alta($paramProducto);
// // var_dump($paramProducto);
// if ($altaProducto){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }



////////////////////////////////////////////////////////baja
// $paramBaja = ['idrol'=>0];
// $baja = $ctrlRol->baja($paramBaja);
// if ($baja){
//     echo "BAJA REALIZADA.\n";
// }else{
//     "ERROR.\n";
// }