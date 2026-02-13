<?php 
//define rutas importantes del proyecto, configura headers HTTP y crea variables globales que se usan en TODO el proyecto
//entiendo que tiene q ir al inicio de cada script php basicamente(?)

header('Content-Type: text/html; charset=utf-8'); //define q tipo de contenido devuelve (HTML en UTF-8)
header ("Cache-Control: no-cache, must-revalidate "); //cvita que el navegador guarde la página en caché

/////////////////////////////
//////CONFIGURACION APP//////
/////////////////////////////

$PROYECTO ='tpfinal_pwd'; //nombre de la carpeta del proyecto

$ROOT =$_SERVER['DOCUMENT_ROOT']."/$PROYECTO/"; //variable que almacena el directorio del proyecto, o sea ruta absoluta al proyecto en el servidor

include_once($ROOT.'util/funciones.php'); //incluye las funciones auxiliares

$INICIO = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/vista/login/login.php"; //URL para redirigir al login

$PRINCIPAL = "Location:http://".$_SERVER['HTTP_HOST']."/$PROYECTO/index.php"; // URL para redirigir al inicio (menú principal)

$_SESSION['ROOT']=$ROOT; //guarda el ROOT en sesión para usarlo en otros archivos
