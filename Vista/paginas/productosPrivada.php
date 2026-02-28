<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA//
//solo CLIENTE tiene porductos porque en los otros dos roles esto se muestra en el inicio
include_once "../../configuracion.php";
$sesion = new Session(); //hago un new de sesion
$nombreRol = $sesion->getRol(); //obtengo el rol que se almacenó en la sesión (o sea cuando se hizo login)
$ctrlProductos = new ProductoController(); //hago un new de la clase productos
$listaProductos = $ctrlProductos->buscar(null); //traigo tooodos los productos almacenados enla bd
?>

<div class="container mt-3">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-info">Catálogo de productos</h1>
        </div>

        <div class="row">
            <?php foreach ($listaProductos as $objProducto) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm"> <img src="<?php echo $objProducto->getProImagen(); ?>" class="card-img-top" alt="<?php echo $objProducto->getNombreProducto(); ?>" style="height: 200px; object-fit: cover;">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $objProducto->getNombreProducto(); ?></h5>
                            <p class="card-text text-muted small"><?php echo $objProducto->getDetalleProducto(); ?></p>
                            <p class="card-text fw-bold fs-5 mt-auto"><?php echo "$" . number_format($objProducto->getPrecioProducto(), 0, ',', '.'); ?></p>

                            <button class="btn btn-info text-white w-100" onclick="agregarAlCarrito(<?php echo $objProducto->getIdProducto(); ?>)">
                                <i class="bi bi-cart-plus"></i> Añadir al carrito
                                <!-- acá como es en la privada si puedo poner la funcion js de agregarAlCarrito(id) -->
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

</div>