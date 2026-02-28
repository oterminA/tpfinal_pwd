<?php
//ESTE ES EL ACTION DEL CARRITO, acá metí lo de las funciones del carritoPrivada.php (actualizar, eliminar, agregarAlCarrito, finalizar)
include_once "../../configuracion.php";
$datos = data_submitted(); //obtengo la info q viene por post/get
$res = ['success' => false, 'msg' => '']; //bandera? o sea armo los datos por defecto

if (isset($datos['accion'])) { //acá obtengo lo que sea que tiene que hacerse xq esto viene desde el jquery/ajax en la vista
    $objSession = new Session(); //new de clase session
    $objCtrlProd = new ProductoController(); //nueva instancia de producto

    switch ($datos['accion']) { //armo el swithc donde van a venir cada una de las funciones de ajax/jqeury
        case 'agregar': //para la funcion agregarAlCarrito(id)
            $id = $datos['idproducto']; //obtengo el id que viene por el post
            $lista = $objCtrlProd->buscar(['idproducto' => $id]); //busco enproductos si exsite el id de ese elemento

            if (count($lista) > 0) { //si existe el id que tendo que trabajar:
                $objProducto = $lista[0]; //recupero el obj al que ese id pertenece
                $idUsuario = $objSession->getIdUsuario(); //agarro el id de ese usuario porque lo saco de la clase session por esa funcion que obtiene el id

                $objCtrlCompra = new CompraController(); //nueva instancia de la clase compra
                $compraActual = $objCtrlCompra->existeCompraEnCurso($idUsuario); //acá recupero el id o null de esa compra de ese usuario, o sea es una compra que está en curso porque no puedo trabajar sobre una compra finalizada o cancelda

                if ($compraActual !== null) { //o sea si no hay una compra preexistente
                    $paramCompra = [ //armo el parametro de la compra para hacerle el alta
                        'cofecha' => date('Y-m-d H:i:s'),
                        'idusuario' => $idUsuario
                    ];

                    $idCompra = $objCtrlCompra->alta($paramCompra); //hago el alta de la compra 

                    if ($idCompra) { //si se pudo hacer el alta
                        $compraActual = $objCtrlCompra->buscar(['idcompra' => $idCompra])[0]; //guardo la compra actual con ese id
                    } else {
                        $res['msg'] = "Error al crear el carrito de la compra"; //mensaje de error que esto va en la marge msg del arreglo que le paso despues al ajax por jquery
                    }
                }

                $objCtrlItem = new CompraItemController(); //hagoun new de compra item (esa clase vincula a producto y a compra(por ende a usuario))
                $paramItem = [ //armo el parametro con la info q la clase necesita
                    'idcompra' => $compraActual->getIdCompraItem(), //el id de
                    'idproducto' => $id,
                    'cicantidad' => 1
                ];

                if ($objProducto->getStockProducto() >= 1) { //verifico si hay stock del producto para poder dar de alta
                    if ($objCtrlItem->alta($paramItem)) {// hago de alta la compra
                        $res['success'] = true;// para el js
                    }
                } else { //si no hay stock
                    $res['msg'] = "No hay stock disponible"; //el msj para el user de que no hay stck
                }
            }
            break;

        case 'actualizar': //para la funcion actualizarCantidad()
            $id = $datos['idproducto']; //recupero el id que viene por post
            $cantidadNueva = $datos['cantidad']; //recupero la cantidad que se tiene que actualizar
            $objCtrlItem = new CompraItemController(); //hago un new de compra-item esa clase vincula a producto y a compra(por ende a usuario))

            $listaProd = $objCtrlProd->buscar(['idproducto' => $id]); //busco ese producto tiene ese id

            if (count($listaProd) > 0 && $listaProd[0]->getStockProducto() >= $cantidadNueva) { //si existe ese producto Y si el stock es mayor oigual que la cantidad que se necesita ahora de ese producto
                if ($objCtrlItem->modificacion(['idcompraitem' => $datos['idcompraitem'], 'cicantidad' => $cantidadNueva])) { //acá hago la modificación de la cantidad del producto que se va pidiendo
                    $res['success'] = true; //para el js
                }
            } else { //si no hay stock
                $res['msg'] = "Stock insuficiente"; //mensaje para el user
            }
            break;

        case 'finalizar': //para la funcion finalizarCompra()
            $idCompra = $datos['idcompra'];//obtengo el id de la compra que viene por post
            $objCtrlCE = new CompraEstadoController(); //new de compraestado (es la q vincula a la compra(q es comoel carrito) y el estado(iniciado, aceptado, finalizado, etc))

            $paramEstado = [//armo el parametro de esa compra para hacer el alta
                'idcompra' => $idCompra,
                'idcompraestadotipo' => 1, //o sea iniciada
                'cefechaini' => date('Y-m-d H:i:s'), //fecha de inciiao
                'cefechafin' => null //porque todavía no finaliza
            ];

            if ($objCtrlCE->alta($paramEstado)) { //si se puede hacer el alta
                $res['success'] = true; //para el js de que se pudo
            }
            break;
    }
}
echo json_encode($res); //de lo que sea muestro el true o false cuando convenga
