<?php
include_once '../../configuracion.php';
$objSession = new Session();

if (!$objSession->validar()) {
    header('Location: login.php');
    exit;
}

$nombreRolActual = $objSession->getRol(); 
$nombreUsuario = $objSession->getUsuario(); 

$rolCtrl = new RolController();
$menuRolCtrl = new MenuRolController();
$menuCtrl = new MenuController();

$idRolActual = $rolCtrl->obtenerRol($nombreRolActual); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>MascotaFeliz</title>
    
    <script src="../js/jquery-4_0_0.js"></script>

    <link rel="stylesheet" href="../jquery-easyui-1.11.4/themes/default/easyui.css">
    <link rel="stylesheet" href="../jquery-easyui-1.11.4/themes/icon.css">
    <script src="../jquery-easyui-1.11.4/jquery.easyui.min.js"></script>
    <script src="../jquery-easyui-1.11.4/locale/easyui-lang-es.js"></script>
    
    <script src="../js/jquery_ajax.js"></script>
</head>
<body class="easyui-layout" style="width:100%; height:100vh;">
    
    <div data-options="region:'north'" style="height:80px; background:#3498db; color:white; padding:20px;">
        <h1 style="margin:0; float:left;">MascotaFeliz</h1>
        <div style="float:right;">
            <p style="margin:0;">Rol: <strong><?php echo ucfirst($nombreRolActual); ?></strong></p>
        </div>
    </div>
    
    <div data-options="region:'west',split:true,title:'Navegación'" style="width:250px;">
        <div class="easyui-accordion" data-options="fit:true, border:false">
        <?php
        if ($idRolActual) {
            $listaMenuRol = $menuRolCtrl->Buscar(['idrol' => $idRolActual]);

            foreach ($listaMenuRol as $item) {
                $objMenu = $item->getObjMenu();
                
                if ($objMenu->getObjMenuPadre() == null && $objMenu->getDeshabilitado() == null) {
                    
                    echo '<div title="' . $objMenu->getNombreMenu() . '" style="padding:10px;">';
                    
                    $hijos = $menuCtrl->Buscar(['idpadre' => $objMenu->getIdMenu()]);
                    
                    foreach ($hijos as $hijo) {
                        if ($hijo->getDeshabilitado() == null) {
                            echo '<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" 
                                     onclick="agregarTab(\'' . $hijo->getNombreMenu() . '\', \'' . $hijo->getMenuDescripcion() . '\')">' 
                                 . $hijo->getNombreMenu() . '</a><br>';
                        }
                    }
                    echo '</div>';
                }
            }
        } else {
            echo "<div style='padding:10px;'>No se encontraron permisos para este rol.</div>";
        }
        ?>
        </div>
    </div>
    
    <div data-options="region:'center'">
        <div id="tabs" class="easyui-tabs" style="width:100%; height:100%;">
            <div title="Inicio" style="padding:20px;">
            <h2>Bienvenido/a <?php echo $objSession->getUsuario(); ?></h2>
                <p>Seleccioná una opción del menú lateral para comenzar.</p>
            </div>
        </div>
    </div>
    
    <div data-options="region:'south'" style="height:50px; background:#2c3e50; color:white; text-align:center; padding:15px;">
        <p style="margin:0;">© 2026 MascotaFeliz - TPFINAL PWD</p>
    </div>
</body>
</html>