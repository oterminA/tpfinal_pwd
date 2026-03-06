<?php
//ESTE SCRIPT ES PARA LA VISTA PUBLICA//
include_once '../../configuracion.php';
$ctrlProductos = new ProductoController(); //hago un new de la clase productos
$listaProductos = $ctrlProductos->buscar(null); //traigo todos los productos

?>

<div class="container mt-3">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-info">Catálogo de productos</h1>
        </div>

        <div class="row">
    <?php foreach ($listaProductos as $objProducto) : 
        $stockActual = $objProducto->getStockProducto();
        
        $estaHabilitado = ($objProducto->getProductoDeshabilitado() == null || $objProducto->getProductoDeshabilitado() == '0000-00-00 00:00:00');

        if ($stockActual > 0 && $estaHabilitado) : 
    ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm"> <img src="<?php echo $objProducto->getProImagen(); ?>" class="card-img-top" alt="<?php echo $objProducto->getNombreProducto(); ?>" style="height: 200px; object-fit: cover;">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $objProducto->getNombreProducto(); ?></h5>
                            <p class="card-text text-muted small"><?php echo $objProducto->getDetalleProducto(); ?></p>
                            <p class="card-text fw-bold fs-5 mt-auto"><?php echo "$" . number_format($objProducto->getPrecioProducto(), 0, ',', '.'); ?></p>

                            <a href="./login.php" class="btn btn-info text-white w-100" >
                                <i class="bi bi-cart-plus"></i> Añadir al carrito
                            </a>
                            <!-- acá cuando elcliente quiera agregar al carrito lo mando al login porque sin cuenta no se puede meter cosas al carrtio -->
                        </div>
                    </div>
                </div>
                <?php 
        endif; 
    endforeach; 
    ?>
        </div>
</div>