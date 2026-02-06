<?php
// Vista/estructura/menu_dinamico.php
include_once '../configuracion.php';

$session = new Session();
$menuHtml = '';

if ($session->activa()) {
    $usuario = $session->getUsuario();
    $ctrlUsuarioRol = new UsuarioRolController();
    $ctrlMenuRol = new MenuRolController();
    $ctrlMenu = new MenuController();
    
    // Obtener roles del usuario
    $rolesUsuario = $ctrlUsuarioRol->buscar(['idusuario' => $usuario->getIdUsuario()]);
    
    $menusPermitidos = [];
    
    // Por cada rol, obtener sus menús
    foreach ($rolesUsuario as $usuarioRol) {
        $idRol = $usuarioRol->getObjRol()->getIdRol();
        
        // Buscar menús asociados a este rol
        $menuRoles = $ctrlMenuRol->buscar(['idrol' => $idRol]);
        
        foreach ($menuRoles as $menuRol) {
            $menu = $menuRol->getObjMenu();
            $idMenu = $menu->getIdMenu();
            
            // Evitar duplicados
            if (!isset($menusPermitidos[$idMenu])) {
                $menusPermitidos[$idMenu] = $menu;
            }
        }
    }
    
    // Construir el HTML del menú
    $menuHtml .= '<div class="easyui-accordion" style="width:100%;">';
    
    // Agrupar menús por padre (menús principales)
    $menusPrincipales = [];
    $submenus = [];
    
    foreach ($menusPermitidos as $menu) {
        if ($menu->getObjMenuPadre() == null) {
            $menusPrincipales[] = $menu;
        } else {
            $idPadre = $menu->getObjMenuPadre()->getIdMenu();
            if (!isset($submenus[$idPadre])) {
                $submenus[$idPadre] = [];
            }
            $submenus[$idPadre][] = $menu;
        }
    }
    
    // Generar acordeón
    foreach ($menusPrincipales as $menuPrincipal) {
        $idMenu = $menuPrincipal->getIdMenu();
        $titulo = $menuPrincipal->getMeNombre();
        
        $menuHtml .= '<div title="' . $titulo . '" style="padding:10px;">';
        
        if (isset($submenus[$idMenu])) {
            $menuHtml .= '<ul style="list-style:none; padding:0;">';
            foreach ($submenus[$idMenu] as $submenu) {
                $menuHtml .= '<li style="padding:5px 0;">';
                $menuHtml .= '<a href="' . $submenu->getMeDescripcion() . '" class="menu-link">';
                $menuHtml .= $submenu->getMeNombre();
                $menuHtml .= '</a>';
                $menuHtml .= '</li>';
            }
            $menuHtml .= '</ul>';
        } else {
            // Si no tiene submenús, es un enlace directo
            $menuHtml .= '<a href="' . $menuPrincipal->getMeDescripcion() . '" class="menu-link">';
            $menuHtml .= 'Ir a ' . $titulo;
            $menuHtml .= '</a>';
        }
        
        $menuHtml .= '</div>';
    }
    
    $menuHtml .= '</div>';
} else {
    // Usuario no logueado - menú público
    $menuHtml = '
    <div class="easyui-accordion" style="width:100%;">
        <div title="Menú" style="padding:10px;">
            <ul style="list-style:none; padding:0;">
                <li style="padding:5px 0;"><a href="inicio.php" class="menu-link">Inicio</a></li>
                <li style="padding:5px 0;"><a href="productos.php" class="menu-link">Productos</a></li>
                <li style="padding:5px 0;"><a href="contacto.php" class="menu-link">Contacto</a></li>
                <li style="padding:5px 0;"><a href="ingresar.php" class="menu-link">Ingresar</a></li>
                <li style="padding:5px 0;"><a href="registrarse.php" class="menu-link">Registrarse</a></li>
            </ul>
        </div>
    </div>';
}

echo $menuHtml;
?>