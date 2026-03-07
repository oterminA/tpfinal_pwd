<?php
//ESTE ES EL ACTION DEL PRODUCTOS, acá metí lo de las funciones del home.php (alta producto, eliminar producto, modificar producto)
include_once "../../configuracion.php";
$datos = data_submitted(); //obtengo la info q viene por post/get
$res = ['success' => false, 'msg' => '']; //bandera? o sea armo los datos por defecto
$objProductoCtrl = new ProductoController();
if (isset($datos['accion'])) { //acá obtengo lo que sea que tiene que hacerse xq esto viene desde el jquery/ajax en la vista

    switch ($datos['accion']) {
        case 'habilitar':
            $param = [
                'idproducto' => $datos['idproducto'],
                'prodeshabilitado' => '0000-00-00 00:00:00' //revisar cómo hago acá para los que reciben si o si null
            ];

            $resultado = $objProductoCtrl->modificacion($param); //hago la modificacion
            if ($resultado) { //si se hizo
                $res['success'] = true;
                $res['msg'] = 'Producto habilitado';
            } else {
                $res['msg'] = 'Error al habilitar';
            }
            break;

        case 'eliminar':
            if (!$datos['idproducto']) { //si no hay un id
                $res['msg'] = 'ID del producto no proporcionado';
            } else { //si hay un id existente
                $fechaActual = date('Y-m-d H:i:s'); //tomo lafecha actual
                $param = [ //hago el param para eliminar/deshabilitar
                    'idproducto' => $datos['idproducto'], //id delproducto a eliminar
                    'prodeshabilitado' => $fechaActual //fecha actual para deshabilitar
                ];

                $resultado = $objProductoCtrl->baja($param); //hago la baja que en realidad hace un borrado logico NO fisico

                if ($resultado) { //si se pudo deshabilitar
                    $res['success'] = true;
                }
            }
            break;

        case 'modificar':
            if (isset($_FILES['proimagen']) && $_FILES['proimagen']['error'] === UPLOAD_ERR_OK) {
                $dir = "../css/Assets/";
                $nombreArchivo = time() . "_" . $_FILES['proimagen']['name'];
                if (move_uploaded_file($_FILES['proimagen']['tmp_name'], $dir . $nombreArchivo)) {
                    $datos['proimagen'] = "../css/Assets/" . $nombreArchivo;
                }
            } else {
                unset($datos['proimagen']);
            }

            if ($objProductoCtrl->modificacion($datos)) {
                $res['success'] = true;
            }
            break;

        case 'alta':
            $existe = $objProductoCtrl->buscar(['pronombre' => $datos['pronombre']]); //reviso q exista el producto
            if (count($existe) > 0) { //si ya existe mando ese mensaje porque el producto no puede darse de alta si ya exits
                $res['msg'] = 'El producto ya existe.';
            } else { //si NO existe el producto en la bd
                if (isset($_FILES['proimagen']) && $_FILES['proimagen']['error'] === UPLOAD_ERR_OK) { //acá me fijo si llegó la imagen y si llegó sin errores
                    $dir = "../css/Assets/";
                    $nombreArchivo = time() . "_" . $_FILES['proimagen']['name']; //time es para evitar duplicados de imagenes xq si tienen el mismo nombre se sobreescriben
                    $archivoDestino = $dir . $nombreArchivo;

                    if (move_uploaded_file($_FILES['proimagen']['tmp_name'], $archivoDestino)) { //acá saco la imagen de la carpeta temporal del servidor y digo que quiero moverla a assets
                        $datos['proimagen'] = "../css/Assets/" . $nombreArchivo;

                        $paramP = [ //armo el param
                            'pronombre' => $datos['pronombre'],
                            'prodetalle' => $datos['prodetalle'],
                            'procantstock' => $datos['procantstock'],
                            'proprecio' => $datos['proprecio'],
                            'prodeshabilitado' => null,
                            'proimagen' => $datos['proimagen']
                        ];
                        $resultado = $objProductoCtrl->alta($paramP);
                        if ($resultado) {
                            $res['success'] = true;
                        }
                    }
                    break;
                }
            }
    }
}
echo json_encode($res); //acá le paso la info de respuesta al jason, en lugar de poner esta sentencia repetida en todos los if, pongo las variables peero el envío lo hago recien acá