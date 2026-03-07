<?php
include_once "../../configuracion.php";
$datos = data_submitted();
$res = ['success' => false, 'msg' => ''];

if (isset($datos['accion'])) {
    $objSession = new Session();
    $objCtrlCompraEstado = new CompraEstadoController(); //relaciona compra-producto y compraestadotipo
    $objCtrlCompraItem = new CompraItemController(); //relaciona compra y producto
    $objCtrlCompra = new CompraController();

    switch ($datos['accion']) {
        case 'despachar':
            $idCompra = $datos['idcompra'];
            $fechaActual = date("Y-m-d H:i:s");

            $listaCompraEstado = $objCtrlCompraEstado->buscar(['idcompra' => $idCompra]);
            $objEstadoActual = null;

            foreach ($listaCompraEstado as $estado) {
                $fFin = $estado->getFechaFin();
                if ($fFin == null || $fFin == "0000-00-00 00:00:00") {
                    $objEstadoActual = $estado;
                    break;
                }
            }

            if ($objEstadoActual != null) {
                $idTipoActual = $objEstadoActual->getObjCet()->getIdCompraEstadoTipo();

                if ($idTipoActual == 2) {
                    $resMod = $objCtrlCompraEstado->modificacion([
                        'idcompraestado' => $objEstadoActual->getIdCompraEstado(),
                        'idcompra' => $idCompra,
                        'idcompraestadotipo' => 2,
                        'cefechaini' => $objEstadoActual->getFechaIni(),
                        'cefechafin' => $fechaActual
                    ]);

                    if ($resMod) {
                        $resAlta = $objCtrlCompraEstado->alta([
                            'idcompra' => $idCompra,
                            'idcompraestadotipo' => 3,
                            'cefechaini' => $fechaActual,
                            'cefechafin' => null
                        ]);

                        if ($resAlta) {
                            $objCompra = $objCtrlCompra->buscar(['idcompra' => $idCompra])[0];
                            MailerControl::enviarCorreoEstado($objCompra, "Enviada (En camino)");
                            
                            $res['success'] = true;
                            $res['msg'] = "Pedido despachado correctamente.";
                        }
                    }
                }
            } else {
                $res['msg'] = "No se encontró un estado activo.";
            }
            break;


            case 'cancelar':
                $objCtrlProd = new ProductoController();
                $idCompra = $datos['idcompra'];
                $fechaActual = date("Y-m-d H:i:s");
            
                $listaCompraEstado = $objCtrlCompraEstado->buscar(['idcompra' => $idCompra, 'cefechafin' => null]);
            
                if (count($listaCompraEstado) > 0) {
                    $objEstadoActual = $listaCompraEstado[0]; 
                    $modificar = $objCtrlCompraEstado->modificacion([
                        'idcompraestado' => $objEstadoActual->getIdCompraEstado(),
                        'idcompra' => $idCompra,
                        'idcompraestadotipo' => $objEstadoActual->getObjCet()->getIdCompraEstadoTipo(),
                        'cefechaini' => $objEstadoActual->getFechaIni(), 
                        'cefechafin' => $fechaActual
                    ]);
            
                    if ($modificar) {
                        $alta = $objCtrlCompraEstado->alta([
                            'idcompra' => $idCompra,
                            'idcompraestadotipo' => 4,
                            'cefechaini' => $fechaActual,
                            'cefechafin' => null
                        ]);

                        $objCompra = $objCtrlCompra->buscar(['idcompra' => $idCompra])[0];
    MailerControl::enviarCorreoEstado($objCompra, "Cancelada");
    
    $res['success'] = true;
            
                        $items = $objCtrlCompraItem->buscar(['idcompra' => $idCompra]);
                        foreach ($items as $item) {
                            $objProd = $item->getObjProducto();
                            $nuevoStock = $objProd->getStockProducto() + $item->getCantidad();
                            $objCtrlProd->modificacion([
                                'idproducto' => $objProd->getIdProducto(), 
                                'procantstock' => $nuevoStock
                            ]);
                        }
                        $res['success'] = true;
                        $res['msg'] = "Compra cancelada y stock restaurado.";
                    }
                } else {
                    $res['msg'] = "No se encontró un estado activo para cancelar.";
                }
                break;
}
echo json_encode($res);
}