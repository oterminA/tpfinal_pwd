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

// $paramUsuario = ['usnombre'=> 'pipo', 'uspass'=> "0123", 'usmail'=> 'pipo@admin.com', 'usdeshabilitado'=> null];
// $altaUsuario = $ctrlUsuario->alta($paramUsuario);
// if ($altaUsuario){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }

// $paramUsuarioRol = ['idrol'=> 3, 'idusuario'=> 6];
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
// 6Mi cuenta
// 7Carrito
// 9Usuarios
// 10Roles
//11 menu
// 27 pedidos

// $paramMenuRol = ['idmenu'=> 10, 'idrol'=> 3];
// $altaMenuRol = $ctrlMenuRol->alta($paramMenuRol);
// if ($altaMenuRol){
//     echo "ALTA REALIZADA.\n";
// }else{
//     echo "ERROR.\n";
// }


// $paramMenu = ['menombre' => 'Pedidos', 'medescripcion' => 'muestra información de las compras y pedidos', 'idpadre' => null, 'medeshabilitado' => null];
// // var_dump($paramMenu);
// $altaMenu = $ctrlMenu->alta($paramMenu);
// if ($altaMenu) {
//     echo "ALTA REALIZADA.\n";
// } else {
//     echo "ERROR EN EL ALTA.\n";
// }

// $paramProducto = ['pronombre'=> 'Plato para comida/agua', 'prodetalle'=> 'Plato para comida/agua perro o gato, indistinto', 'procantstock'=> 2, 'proprecio'=> 12000, 'prodeshabilitado'=> null, 'proimagen' => 'Vista/css/Assets/plato_mascota.jpg'];
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


////prueba para ver si funciona lo de rol menu
////ver el listado de roles
// $listaRoles = $ctrlRol->buscar(null);
// if (is_array($listaRoles) && count($listaRoles) > 0) {
//     foreach ($listaRoles as $rol) {
//         echo $rol . "\n";
//     }
// }else{
//     echo "error\n";
// }

//ver el listado de menues
// $listaMenúes = $ctrlMenu->buscar(null);
// if (is_array($listaMenúes) && count($listaMenúes) > 0) {
//     foreach ($listaMenúes as $menu) {
//         echo $menu . "\n";
//     }
// }else{
//     echo "error\n";
// }


//ver el listado de menurol
// $listaMR = $ctrlMenuRol->buscar(null);
// if (is_array($listaMR) && count($listaMR) > 0) {
//     foreach ($listaMR as $menurol) {
//         $objMenu = $menurol->getObjMenu();
//         $objRol = $menurol->getObjRol();
//         $rol = $objRol->getRolDescripcion();
//         $menu = $objMenu->getNombreMenu();

//         echo "*******MENÚ DINÁMICO*******\n";
//         echo "> ROL: " . $rol . "\n";
//         echo " tiene \n";
//         echo "> SECCIÓN DEL MENÚ: " . $menu . "\n";
//     }
// }else{
//     echo "error\n";
// }


$paramAltaCE = [
    'idcompra' => 28,
    'idcompraestadotipo' => 1,
    'cefechaini' => date('Y-m-d H:i:s'),
    'cefechafin' => null
];
$alta = $ctrlCompraEstado->alta($paramAltaCE);
if($alta){
    echo "ALTA REALIZADA.\n";
}else{
echo "ERROR\n";
}