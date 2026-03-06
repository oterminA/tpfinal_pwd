<?php
//ESTE SCRIPT ES LO QUE VE EL USUARIO CON ROL CLIENTE//
/*la onda sería:
COMPRA---> es el carrito, representa al carrito e involucra al usuario y la hora en que se crea ese carrito y creo que inmediatamete involucra a producto y a compraitem, se crea apenas el producto se agrega al carrito
PRODUCTO ---> es el producto, los elementos que pueden adquirirse
COMPRAITEM ---> es la vinculacion entre una compra y un producto, el producto metido al carrito por un usuario a una hora especifica, se crea apenas el producto se agrega al carrito vinculando ambas clases
COMPRAESTADOTIPO ---> el estado que puede tener la compra pero no es la compra, solo la palabra 'iniciada' por ejemplo, es algo estático porque se inica una compra, se acepta, se cancela y demás, o sea de acá saco los estados que puede tener una compra
COMPRAESTADO---> relaciona una compra y un estado, por eso tiene las referencias a la clase compra y a la compra estado, por ejemplo: compra con id 123 tiene estado "aceptada", estos cambios los hace el rol DEPOSITO
ENTONCES: producto con compra(y user) se unen ----> compraitem inmediatamente se crea---> compraestado al toque se crea, que a su vez usa compraestadotipo*/
include_once "../../configuracion.php";
$objSession = new Session(); //new de sesion

$objCtrlCompra = new CompraController(); // new de compra
$objCtrlItem = new CompraItemController(); //new de compraitem q es la clase que relaciona al producto y la compra
$idUsuario = $objSession->getIdUsuario(); //desde la sesion obtengo el id del usuario
$compraActiva = $objCtrlCompra->existeCompraEnCurso($idUsuario); //compra activa es una variable que guarda el id de la compra en curso o null si no hay nada
$itemsCarrito = []; //array vacio
if ($compraActiva != null) { //si la compraactiva no es null, o sea si SI hay compra en este momento
    $idCompra = is_object($compraActiva) ? $compraActiva->getIdCompra() : $compraActiva; //recupero el id de dicha compra
    $itemsCarrito = $objCtrlItem->buscar(['idcompra' => $idCompra]); //listo los productoa que están enla clase compraitem
}
$subtotalGeneral = 0; //variable del sutotal en cero o sea del total porque se le sumaria el costo de envio que yo no pongo
?>

<div class="container my-4">
    <div class="row">
        <div class="col-xl-8">
            <h4 class="mb-3 text-info"><i class="bi bi-cart3"></i> Mi Carrito</h4>

            <?php if (empty($itemsCarrito)) : ?>
                <div class="alert alert-info">Tu carrito está vacío :c</div>
            <?php else : ?>
                <?php foreach ($itemsCarrito as $item) : //listo la info de cada producto que está en el carrito 
                    $objProducto = $item->getObjProducto();
                    $cantComprar = $item->getCantidad();
                    $precio = $objProducto->getPrecioProducto();
                    $totalItem = $precio * $cantComprar; //el costo de un producto * la cantidad de items de ese producto que se compra
                    $subtotalGeneral += $totalItem; //acá es el subtotal + el total del item
                ?>
                    <div class="card border shadow-none mb-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start border-bottom pb-3">
                                <div class="me-4">
                                    <img src="<?php echo $objProducto->getProImagen(); ?>" alt="" class="avatar-lg rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 align-self-center overflow-hidden">
                                    <div>
                                        <h5 class="text-truncate font-size-18">
                                            <a href="#" class="text-dark"><?php echo $objProducto->getNombreProducto(); ?></a>
                                        </h5>
                                        <p class="text-muted mb-0 small"><?php echo $objProducto->getDetalleProducto(); ?></p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-2">
                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarDelCarrito(<?php echo $item->getIdCompraItem(); ?>, <?php echo $objProducto->getIdProducto(); ?>)">
                                        <!-- funcion para eliminar un producto del carrito -->
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <p class="text-muted mb-1 small">Precio Unitario</p>
                                    <h5 class="precio-unitario" data-valor="<?php echo $precio; ?>">
                                        $<?php echo number_format($precio, 0, ',', '.'); ?>
                                    </h5>
                                </div>
                                <div class="col-md-5">
                                    <p class="text-muted mb-1 small">Cantidad</p>
                                    <div class="d-inline-flex">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" value="<?php echo $cantComprar; ?>" min="1" max="<?php echo $objProducto->getStockProducto(); ?>" onchange="actualizarCantidad(<?php echo $item->getIdCompraItem(); ?>, this.value)">
                                        <!-- esta es la ruedita esa para ir agregandole cantidades y es onchange porque necesito que sea cuando el valorcambia no cuando se cliquea -->
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-muted mb-1 small">Subtotal</p>
                                    <h5 class="text-primary" id="subtotal-item-<?php echo $item->getIdCompraItem(); ?>">
                                        $<?php echo number_format($totalItem, 0, ',', '.'); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="row my-4">
                <div class="col-sm-6">
                    <button onclick="mostrarSeccion(1)" class="btn btn-link text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Seguir comprando
                    </button>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <?php if (!empty($itemsCarrito)) : ?>
                        <button onclick="finalizarCompra(<?php echo $idCompra; ?>)" class="btn btn-success px-4 rounded-pill">
                            <i class="bi bi-check-circle me-1"></i> Confirmar pedido
                        </button>
                        <!-- este boton finaliza/confirma la compra, tiene su funcion más abajo y su action está en carrito.php -->
                    <?php endif; ?>
                    <!-- pongo endif que es lo mismo que poner la llave del if pero se hace más legible cuando se trabaja conhtml y php -->
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border shadow-none bg-light">
                <div class="card-header bg-transparent border-bottom py-3 px-4">
                    <h5 class="font-size-16 mb-0">Resumen de compra</h5>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td>Sub Total:</td>
                                    <td class="text-end">$<?php echo number_format($subtotalGeneral, 0, ',', '.'); ?></td>
                                    <!-- el number format espera 4 parametros y lo pongo para mostrar algo como $3.000.000 -->
                                </tr>
                                <tr>
                                    <td>Envío:</td>
                                    <td class="text-end text-success">Gratis siempre</td>
                                </tr>
                                <tr class="border-top border-dark">
                                    <th>Total:</th>
                                    <td class="text-end">
                                        <span class="fw-bold fs-4">$<?php echo number_format($subtotalGeneral, 0, ',', '.'); ?></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../js/jquery_ajax.js"></script>