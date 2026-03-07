<?php
include_once "../../configuracion.php";
$datos = data_submitted();
$res = ['success' => false, 'msg' => ''];

if (isset($datos['accion'])) {
    $objSession = new Session();
    $objCtrlProd = new ProductoController();
    $objCtrlItem = new CompraItemController();

    switch ($datos['accion']) {
        case 'agregar':
            $id = $datos['idproducto'];
            $lista = $objCtrlProd->buscar(['idproducto' => $id]);
            $objCtrlCompra = new CompraController();
            $objCtrlCEstado = new CompraEstadoController();

            if (count($lista) > 0) {
                $objProducto = $lista[0];
                $stockDisponible = $objProducto->getStockProducto();
                $idUsuario = $objSession->getIdUsuario();

                if ($stockDisponible < 1) {
                    $res['msg'] = "El producto no tiene stock disponible.";
                } else {
                    $existeCompra = $objCtrlCompra->existeCompraEnCurso($idUsuario);
                    $objCompraFinal = null;

                    if ($existeCompra === null) {
                        $paramCompra = ['cofecha' => date('Y-m-d H:i:s'), 'idusuario' => $idUsuario];
                        if ($objCtrlCompra->alta($paramCompra)) {
                            $comprasDelUser = $objCtrlCompra->buscar(['idusuario' => $idUsuario]);
                            $objCompraFinal = end($comprasDelUser);
                            $paramCompraEstado = [
                                'idcompra' => $objCompraFinal->getIdCompra(),
                                'idcompraestadotipo' => 1, //iniciada "cuando el usuario : cliente inicia la compra de uno o mas productos del carrito"
                                'cefechaini' => date('Y-m-d H:i:s'),
                                'cefechafin' => NULL
                            ];
                            $altaCompraEstado = $objCtrlCEstado->alta($paramCompraEstado);
                        }
                    } else {
                        $objCompraFinal = is_object($existeCompra) ? $existeCompra : $objCtrlCompra->buscar(['idcompra' => $existeCompra])[0];
                    }

                    if ($objCompraFinal != null) {
                        $idCompraActiva = $objCompraFinal->getIdCompra();
                        $itemPrevio = $objCtrlItem->buscar(['idcompra' => $idCompraActiva, 'idproducto' => $id]);

                        if (count($itemPrevio) > 0) {
                            $objItem = $itemPrevio[0];
                            $cantidadActualEnCarrito = $objItem->getCantidad();

                            if ($cantidadActualEnCarrito < $stockDisponible) {
                                $nuevaCant = $cantidadActualEnCarrito + 1;
                                $res['success'] = $objCtrlItem->modificacion([
                                    'idcompraitem' => $objItem->getIdCompraItem(),
                                    'idcompra' => $idCompraActiva,
                                    'idproducto' => $id,
                                    'cicantidad' => $nuevaCant
                                ]);
                            } else {
                                $res['msg'] = "Error, alcanzaste el límite de stock.";
                            }
                        } else {
                            $res['success'] = $objCtrlItem->alta([
                                'idcompra' => $idCompraActiva,
                                'idproducto' => $id,
                                'cicantidad' => 1
                            ]);
                        }
                    }
                }
            }
            break;

        case 'actualizar':
            $idCI = $datos['idcompraitem'];
            $cantidadNueva = $datos['cicantidad'];
            $objCtrlItem = new CompraItemController();

            $listaItems = $objCtrlItem->buscar(['idcompraitem' => $idCI]);

            if (count($listaItems) > 0) {
                $objItem = $listaItems[0];
                $objProducto = $objItem->getObjProducto();
                if ($objProducto->getStockProducto() >= $cantidadNueva) {

                    $paramModItem = [
                        'idcompraitem' => $idCI,
                        'idcompra' => $objItem->getObjCompra()->getIdCompra(),
                        'idproducto' => $objProducto->getIdProducto(),
                        'cicantidad' => $cantidadNueva
                    ];

                    if ($objCtrlItem->modificacion($paramModItem)) {
                        $res['success'] = true;
                    }
                }
            }
            break;


        case 'eliminar':
            $idCompraItem = $datos['idcompraitem'] ?? null;

            if ($idCompraItem != null) {
                $objCtrlItem = new CompraItemController();

                $listaItem = $objCtrlItem->buscar(['idcompraitem' => $idCompraItem]);

                if (count($listaItem) > 0) {
                    $paramBaja = ['idcompraitem' => $idCompraItem];

                    if ($objCtrlItem->baja($paramBaja)) {
                        $res['success'] = true;
                        $res['msg'] = "Producto eliminado del carrito.";
                    }
                } else {
                    $res['msg'] = "El item no existe.";
                }
            } else {
                $res['msg'] = "ID de item no proporcionado.";
            }
            break;


        case 'finalizar':
            $idCompra = $datos['idcompra'];
            $objCtrlCEstado = new CompraEstadoController();
            $objCtrlProd = new ProductoController();
            $objCtrlItem = new CompraItemController();
            $ahora = date('Y-m-d H:i:s');
            $success = false;

            if ($idCompra != null) {
                $listaEstados = $objCtrlCEstado->buscar([
                    'idcompra' => $idCompra,
                    'idcompraestadotipo' => 1,
                    'cefechafin' => null
                ]);

                if (count($listaEstados) > 0) {
                    $objCE_Actual = $listaEstados[0];

                    $paramModCE = [
                        'idcompraestado' => $objCE_Actual->getIdCompraEstado(),
                        'idcompra' => $idCompra,
                        'idcompraestadotipo' => 1,
                        'cefechaini' => $objCE_Actual->getFechaIni(),
                        'cefechafin' => $ahora
                    ];

                    if ($objCtrlCEstado->modificacion($paramModCE)) {

                        $paramAlta = [
                            'idcompra' => $idCompra,
                            'idcompraestadotipo' => 2,
                            'cefechaini' => $ahora,
                            'cefechafin' => null
                        ];

                        if ($objCtrlCEstado->alta($paramAlta)) {
                            $listaCompraItem = $objCtrlItem->buscar(['idcompra' => $idCompra]);

                            foreach ($listaCompraItem as $objCompraItem) {
                                $objProducto = $objCompraItem->getObjProducto();
                                $cantidadComprada = $objCompraItem->getCantidad();
                                $stockActual = $objProducto->getStockProducto();

                                $stockNuevo = $stockActual - $cantidadComprada;

                                $objCtrlProd->modificacion([
                                    'idproducto' => $objProducto->getIdProducto(),
                                    'procantstock' => $stockNuevo
                                ]);
                            }

                            $objCompra = $objCtrlCompra->buscar(['idcompra' => $idCompra])[0];
                            MailerControl::enviarCorreoEstado($objCompra, "Aceptada (En preparación)");

                            $success = true;
                        }
                    }
                }
            }

            if ($success) {
                $res['success'] = true;
                $res['msg'] = "Compra finalizada con éxito.";
            } else {
                $res['success'] = false;
                $res['msg'] = "No se pudo finalizar la compra.";
            }
            break;
    }

    echo json_encode($res);
}
