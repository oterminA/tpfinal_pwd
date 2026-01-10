<?php
//acá voy a guardar las funciones auxiliares

define('ROOT', dirname(__DIR__) . '/'); 

//////DATA_SUBMITTED: esta funcion es para encapsular los envios de post/get de la info del formulario
function data_submitted()
{
    $datos = [];

    if (!empty($_POST)) {
        $datos = $_POST;
    } elseif (!empty($_GET)) {
        $datos = $_GET;
    } //evaluo si vienen por post o get

    foreach ($datos as $clave => $valor) {
        $datos[$clave] = ($valor === "") ? null : $valor;
    }

    if (!empty($_FILES)) {
        foreach ($_FILES as $clave => $archivo) {
            $datos[$clave] = $archivo;
        }
    }

    return $datos; //retorno los datos del arreglo en un array con la clve siendo el name de la etiqueta y el valor siendo el contenido
}



//////AUTOLOAD: esta funcion es para incluir 'dinamicamente' los objetos del control y del modelo, en lugar de hacer include_once todo el tiempo
spl_autoload_register(function ($className) {
    $directorios = [ //las sgtes son las rutas donde buscar las clases
        ROOT . 'Modelo/', 
        ROOT . 'Modelo/TP5/',         
        ROOT . 'Modelo/conector/', 
        ROOT . 'Control/',
        ROOT . 'Control/TP5/',
    ];

    foreach ($directorios as $directorio) {
        $archivo = $directorio . $className . '.php';
        if (file_exists($archivo)) {
            require_once $archivo;
            return;
        }
    }

    error_log("Autoload: Clase '$className' no encontrada en: " . implode(', ', $directorios));//msj por si falla
});
