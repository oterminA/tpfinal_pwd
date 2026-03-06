<?php
include_once '../../configuracion.php';

$objCtrlCompraEstado = new CompraEstadoController(); //relaciona compra-producto y compraestadotipo
$objCtrlCompraItem = new CompraItemController(); //relaciona compra y producto

$comprasPendientes = $objCtrlCompraEstado->buscar(['idcompraestadotipo' => 2, 'cefechafin' => null]); //recupero las comrpas de comrpaestado cuyo estado es aceptada y que no tienenfecha de fin
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
            <th>Fecha Aceptada</th>
            <th>Estado Actual</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($comprasPendientes) > 0) : ?> 
            <!-- si haycompras pendientes en compraestado -->
            <?php foreach ($comprasPendientes as $objCompraEstado) : 
            // itero sobre cada obj compra estado
                $objCompra = $objCompraEstado->getObjCompra(); //recupero el obj compras que está delegado 
                $listaItems = $objCtrlCompraItem->buscar(['idcompra' => $objCompra->getIdCompra()]); //en compra item busco el id de la compra de arriba
                $usuarioCompro = $objCompra->getObjUsuario()->getNombre(); //recupero desde el obj compra el nombre del user que hizo la compra
            ?>
                <tr>
                    <td><span class="fw-bold"><?php echo $objCompra->getIdCompra(); ?></span></td>
                    <!-- desde el obj compra recupero su id -->
                    <td><?php echo $usuarioCompro; ?></td>
                    <td>
                        <ul class="small mb-0">
                            <?php foreach ($listaItems as $item) : ?>
                                <!-- itero sobre los objetos de compra items y voy recuperando uno auno -->
                                <li><?php echo $item->getObjProducto()->getNombreProducto() . " (Cant: " . $item->getCantidad() . ")"; ?></li>
                                <!-- //de ese item de compra item recupero el obj producto relacionado y el nombre de ese obj producto, despues del obj item recupero la cantidad que pidió el user -->
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td><?php echo $objCompraEstado->getFechaIni(); ?></td>
                    <td><span class="badge bg-primary">Aceptada</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-success" onclick="despacharCompra(<?php echo $objCompra->getIdCompra(); ?>)">
                             Despachar compra
                        </button>
                        
                        <button class="btn btn-sm btn-danger" onclick="cancelarCompra(<?php echo $objCompra->getIdCompra(); ?>)">
                             Cancelar compra
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="6" class="text-center p-4 text-muted">No hay pedidos pendientes de despacho.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<script>
    function despacharCompra(idC) {
        confirm("¿Confirmar despacho del pedido?");
        
        if (confirm) {
            $.ajax({
                type: "POST",
                url: '../Action/gestionDeposito.php', 
                data: { 
                    accion: accion, 
                    idcompra: idC 
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alert("Pedido actualizado correctamente");
                        location.reload();
                    } else {
                        alert("Error");
                    }
                }
            });
        }
    }

    function cancelarCompra(idC) {
       confirm("¿Estás seguro de cancelar esta compra? El stock será devuelto a la tienda.");
        if (confirm) {
            $.ajax({
                type: "POST",
                url: '../Action/gestionDeposito.php', 
                data: { 
                    accion: accion, 
                    idcompra: idC 
                },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.success) {
                        alert("Pedido cancelado y devuelto a la tienda correctamente");
                        location.reload();
                    } else {
                        alert("Error");
                    }
                }
            });
        }
    }
</script>