<?php
include_once '../../configuracion.php';

$objCtrlCompraEstado = new CompraEstadoController();
$objCtrlCompraItem = new CompraItemController();

$todosLosEstadosActuales = $objCtrlCompraEstado->buscar(['idcompraestadotipo' => 2, 'cefechafin' => '0000-00-00 00:00:00']); //NO BORRAR ESTO QUE ES LO QUE HACE QUE SE QUEDEN SOLO LOS ESTADOS QUE QUIERO Y QUE NO SE DUPLIQUEEEN

$comprasGestion = [];
foreach ($todosLosEstadosActuales as $objCompraEstado) {
    if ($objCompraEstado->getObjCet()->getIdCompraEstadoTipo() != 1) {
        $comprasGestion[] = $objCompraEstado;
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Gestión de pedidos</h2>
</div>

<table class="table table-hover shadow-sm bg-white">
    <thead class="table-dark">
        <tr>
            <th>ID Compra</th>
            <th>Usuario que compró</th>
            <th>Productos</th>
            <th>Fecha Estado</th>
            <th>Estado Actual</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($comprasGestion) > 0) : ?>
            <?php foreach ($comprasGestion as $objCompraEstado) :
                $objCompra = $objCompraEstado->getObjCompra();
                $listaItems = $objCtrlCompraItem->buscar(['idcompra' => $objCompra->getIdCompra()]);
                $usuarioCompro = $objCompra->getObjUsuario()->getNombre();
                $idEstado = $objCompraEstado->getObjCet()->getIdCompraEstadoTipo();
            ?>
                <tr>
                    <td><span class="fw-bold"><?php echo $objCompra->getIdCompra(); ?></span></td>
                    <td><?php echo $usuarioCompro; ?></td>
                    <td>
                        <ul class="small mb-0">
                            <?php foreach ($listaItems as $item) : ?>
                                <li><?php echo $item->getObjProducto()->getNombreProducto() . " (x" . $item->getCantidad() . ")"; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td><?php echo $objCompraEstado->getFechaIni(); ?></td>
                    <td>
                        <?php switch ($idEstado) {
                            case 2:
                                echo '<span class="badge bg-success">Aceptada</span>';
                                break;
                            case 3:
                                echo '<span class="badge bg-primary">Enviada</span>';
                                break;
                            case 4:
                                echo '<span class="badge bg-danger">Cancelada</span>';
                                break;
                        } ?>
                    </td>
                    <td class="text-center">
                        <?php if ($idEstado == 2) : ?>
                            <button class="btn btn-sm btn-primary" onclick="despacharCompra(<?php echo $objCompra->getIdCompra(); ?>)">
                                Despachar
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="cancelarCompra(<?php echo $objCompra->getIdCompra(); ?>)">
                                Cancelar
                            </button>
                        <?php else : ?>
                            <span class="text-muted small">Sin acciones disponibles</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" class="text-center p-4 text-muted">No hay pedidos para gestionar.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    function despacharCompra(idC) {
        let respuesta = confirm("¿Confirmar envío del pedido?");

        if (respuesta) {
            $.ajax({
                type: "POST",
                url: '../Action/pedidos.php',
                data: {
                    accion: 'despachar',
                    idcompra: idC
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alert("Pedido enviado correctamente.");
                        location.reload();
                    } else {
                        alert("Error");
                    }
                }
            });
        }
    }

    function cancelarCompra(idC) {
        let respuesta = confirm("¿Quiere de cancelar esta compra? El item será devuelto a la tienda.");
        if (respuesta) {
            $.ajax({
                type: "POST",
                url: '../Action/pedidos.php',
                data: {
                    accion: 'cancelar',
                    idcompra: idC
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alert("Pedido cancelado. El stock ha sido reintegrado.");
                        location.reload();
                    } else {
                        alert("Error");
                    }
                }
            });
        }
    }
</script>