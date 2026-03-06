<?php
//ESTE SCRIPT ES EL INICIO DE LA VISTA PUBLICA//
include_once "../../configuracion.php";
$ctrlProductos = new ProductoController(); //hago un new de productos
$listaProductos = $ctrlProductos->buscar(null); //traigo todos los productos que esten guardados como tal
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MascotaFeliz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">MascotaFeliz</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <button class="nav-link" href="javascript:void(0)" onclick="mostrarSeccionPublica('productos')">Productos</button>
                            <!-- acá estoy usando una funcioón que tiene un switch donde muestra el contenido segun lo que entre por parametro -->
                            <!-- cambié el <a> por el button porque leí que actualmente se prefiere el button antes de que a -->
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" href="javascript:void(0)" onclick="mostrarSeccionPublica('contacto')">Contacto</button>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-secondary fw-bold" href="login.php">Ingresar</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main id="contenido-publico" class="container mt-4">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-info">MascotaFeliz</h1>
            <h2 class=" text-black">Tenemos lo mejor para tu mascota</h2>
            <h4 class=" text-black">Novedades de la semana</h4>
        </div>

        <div class="row g-4">
            <?php if (count($listaProductos) > 0) : ?>
                <?php foreach ($listaProductos as $objProducto) :
                    $cantidad = $objProducto->getStockProducto();
                    $fechaDeshabilitado = $objProducto->getProductoDeshabilitado();

                    $estaHabilitado = ($fechaDeshabilitado == null || $fechaDeshabilitado == '0000-00-00 00:00:00' || $fechaDeshabilitado == '');

                    if ($cantidad > 7 && $estaHabilitado) :
                        $rutaImagen = $objProducto->getProImagen(); //acá lo que hice es que para que se muestren algunos productos y no elegirlos a mano, puse la condicion de que los productos con stock mayor a 12 son los que se muestran en el inicio, como para variar lo que se muestre
                ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 shadow-sm border-0">
                                <div style="height: 180px; overflow: hidden; background-color: #f8f9fa;">
                                    <img src="<?php echo $rutaImagen; ?>" class="card-img-top w-100 h-100" style="object-fit: contain; padding: 10px;" alt="...">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title h6 fw-bold"><?php echo $objProducto->getNombreProducto(); ?></h5>
                                    <p class="card-text text-muted small flex-grow-1"><?php echo $objProducto->getDetalleProducto(); ?></p>
                                    <span class="fs-5 fw-bold text-primary mb-3">$<?php echo number_format($objProducto->getPrecioProducto(), 0, ',', '.'); ?></span>
                                    <a href="./login.php" class="btn btn-info text-white w-100">
                                        <i class="bi bi-cart-plus"></i> Añadir al carrito
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php endif;
                endforeach; ?>
            <?php else : ?>
                <!-- si $listaProductos no tiene productos-->
                <div class="alert alert-info w-100 text-center">No hay productos disponibles.</div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>&copy; 2026 MascotaFeliz - TPFINAL PWD</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery_ajax.js"></script>
</body>

</html>