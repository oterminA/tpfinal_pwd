<?php
//ESTE SCRIPT ES LO QUE VE EL USUARIO CON ROL CLIENTE//
include_once "../../configuracion.php";
$objSession = new Session(); //hago un new de sesion porque tengo que guardar los datos del carrito ahí adentro
$itemsCarrito = $_SESSION['carrito'] ?? []; //hago la variable de lositems del carrito 
$subtotalGeneral = 0; //incializo en cero
?>

<div class="container my-4">
    <div class="row">
        <div class="col-xl-8">
            <h4 class="mb-3 text-info"><i class="bi bi-cart3"></i> Mi Carrito</h4>

            <?php foreach ($itemsCarrito as $item) : //hago un foreach por cada item del carrito
                $objProducto = $item['producto']; 
                $cantComprar = $item['cantidad'];
                $precio = $objProducto->getPrecioProducto();
                $totalItem = $precio * $cantComprar; //por ejemplo $5000 * 5unidades o algo así
                $subtotalGeneral += $totalItem; // sumo subtotal + el total del item en particular
            ?>
                <div class="card border shadow-none mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start border-bottom pb-3">
                            <div class="me-4">
                                <img src="<?php echo $objProducto->getProImagen(); ?>" alt="" class="avatar-lg rounded" style="width: 80px; height: 80px; object-fit: cover;"> 
                                <!-- para mostrar las imagenes de los productos -->
                            </div>
                            <div class="flex-grow-1 align-self-center overflow-hidden">
                                <div>
                                    <h5 class="text-truncate font-size-18">
                                        <a href="#" class="text-dark"><?php echo $objProducto->getNombreProducto(); ?></a>
                                        <!-- nombre del producto -->
                                    </h5>
                                    <p class="text-muted mb-0 small"><?php echo $objProducto->getDetalleProducto(); ?></p>
                                    <!-- detalle del producto, descripcion -->
                                </div>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarDelCarrito(<?php echo $objProducto->getIdProducto(); ?>)">
                                <!-- boton para eliminar el producto del carrito q tiene su funcion acá y el action carrito.php -->
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <p class="text-muted mb-1 small">Precio Unitario</p>
                                <h5>$<?php echo number_format($precio, 0, ',', '.'); ?></h5>
                            </div>
                            <div class="col-md-5">
                                <p class="text-muted mb-1 small">Cantidad</p>
                                <div class="d-inline-flex">
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" value="<?php echo $cantComprar; ?>" min="1" max="<?php echo $objProducto->getStockProducto(); ?>" onchange="actualizarCantidad(<?php echo $objProducto->getIdProducto(); ?>, this.value)">
                                    <!-- acá muestro ese cosito para ir subiendo la cantidad de items del mismo producto y el tope es la cantidad de stock que hay para ese producto y el this.value hace referencia a la cantidad a la que lo subió o bajó el usuario-->
                                </div>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Subtotal</p>
                                <h5 class="text-primary">$<?php echo number_format($totalItem, 0, ',', '.'); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="row my-4">
                <div class="col-sm-6">
                    <button onclick="mostrarSeccion(1)" class="btn btn-link text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Seguir comprando
                    </button>
                    <!-- para mandar a los productos -->
                </div>
                <div class="col-sm-6 text-sm-end">
                    <?php if (!empty($itemsCarrito)) : ?>
                        <button onclick="finalizarCompra()" class="btn btn-success px-4 rounded-pill">
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
                                    <td class="text-end text-success">Gratis porque es de croto cobrarlo</td>
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
