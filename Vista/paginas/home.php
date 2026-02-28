<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA, SE ACCEDE CON AUTENTICACIÓN DE CREDENCIALES
// acá yo armaría el menú dinamico, cada user va a ver esto ajustado a su rol, el index.php es para todos
include_once '../../configuracion.php';
$objSession = new Session(); // hago un new de session
if (!$objSession->validar()) { //si se puede validar la sesión se continua y sino se redirije al login para que se ingrese correctamente
    header('Location: login.php');
    exit;
}
$nombreRol = $objSession->getRol(); //traigo el nombre del rol del usuario que tiene la sesión activa AHORA por ejemplo si el user es 'caro' el rol es 'admin'
$nombreUsuario = $objSession->getUsuario(); //recupero el usuario que está en la sesión
// var_dump($nombreUsuario);
$rolCtrl = new RolController(); //new de la clase rol
$menuCtrl = new MenuController(); //new de la clase menu
$menuRolCtrl = new MenuRolController(); //new de la clase menu rol que es la que maneja la relacion de qué menu se ve segun el rol

$idRol = $rolCtrl->obtenerRol($nombreRol); //obtengo el id del rol desde la clase rol

$ctrlProductos = new ProductoController(); //hago un new de productos
$listaProductos = $ctrlProductos->buscar(null); //traigo todos los productos que esten guardados como tal
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MascotaFeliz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/styleEstructura.css">

</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="../paginas/home.php">MascotaFeliz</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">

                    <ul class="navbar-nav ms-auto" id="contenedor-menu">
                        <?php
                        if ($idRol) { //si hay un idrol
                            $listaMenuRol = $menuRolCtrl->buscar(['idrol' => $idRol]); //busco en menurol  el objmenurol que tenga ese id

                            if (is_array($listaMenuRol)) { //si es un array lleno
                                foreach ($listaMenuRol as $item) { //itero sobre ese array mostrando cada clase menú o sea cada sección de menú que es´te habilitada para ese rol en específico
                                    $objMenu = $item->getObjMenu(); //recupero el obj menu que tiene ese objeto menurol

                                    if ($objMenu->getDeshabilitado() == null) { //si ese objeto menu es null quiere decir que NO está deshabilitado sino tendría una fecha
                                        echo '<li class="nav-item">';
                                        echo '  <a class="nav-link" href="javascript:void(0)"  
                               onclick="mostrarSeccion(' . $objMenu->getIdMenu() . ')">'
                                            . $objMenu->getNombreMenu() . '</a>';
                                        echo '</li>'; //armo el navlink que tiene la funcion js mostrarSeccion(ej id 3, nombre Productos)
                                    }
                                }
                            }
                        } 
                        ?>
                        <li class="nav-item">
                            <button class="nav-link text-warning" onclick="cerrarSesion()">
                                <i class="bi bi-box-arrow-right"></i> Salir
                                <!-- boton simulado para salir, redirecciona al accion que cierra session haciendo session destroy y unset -->
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <main id="contenido-central" class="container mt-4">
        <div class="alert alert-info shadow-sm border-0 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle fs-4 me-2"></i>
                <strong>¡Bienvenida/o, <?php echo $nombreUsuario; ?>!</strong>
            </div>
            <div class="d-flex align-items-center text-secondary">
                <i class="bi bi-briefcase me-2"></i>
                <small>Rol: <strong><?php echo $nombreRol; ?></strong></small>
                <!-- me parece interesante poner que rol tiene el user para que lo vea porque también me sirvió a mi para saber con qué estaba trabajando -->
            </div>
        </div>

        <div class="container my-5">

    <?php if ($nombreRol === "admin") : ?>
        <!-- si el rol de la persona que tiene la sesion ahora es admin hago todo el resto -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-info">Inventario de productos</h1>
        </div>
        <table class="table table-hover shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaProductos as $objProducto) : ?>
                    <tr>
                        <td><?php echo $objProducto->getIdProducto(); ?></td>
                        <td><?php echo $objProducto->getNombreProducto(); ?></td>
                        <td>$<?php echo number_format($objProducto->getPrecioProducto(), 0, ',', '.'); ?></td>
                        <td>
                            <span class="badge <?php echo $objProducto->getStockProducto() < 10 ? 'bg-danger' : 'bg-success'; ?>">
                                <!-- acá le puse que si el nro de stock es menor a 10, por poner un nro, que el boton sea danger como para que se distinga, sino que se muestre el boton verde -->
                                <?php echo $objProducto->getStockProducto(); ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" onclick="editarProducto(<?php echo $objProducto->getIdProducto(); ?>)"><i class="bi bi-pencil"></i></button>
                            <!-- acá tengo un boton con una accion que es la funcion js de editarProducto(id) que va a estar en un action gestionada -->
                            <button class="btn btn-sm btn-danger" onclick="eliminarProducto(<?php echo $objProducto->getIdProducto(); ?>)"><i class="bi bi-trash"></i></button>
                            <!-- acá tengo un boton con una accion que es la funcion js de eliminarProducto(id) que va a estar en un action gestionada -->
                            <button class="btn btn-sm btn-success" onclick="habilitarProducto(<?php echo $objProducto->getIdProducto(); ?>)"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <!-- acá tengo un boton con una accion que es la funcion js de habilitarProducto(id) que va a estar en un action gestionada -->
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($nombreRol === "cliente") : ?>
        <!-- si el rol de la persona que tiene la sesion ahora es cleinte hago todo el resto -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-info">Novedades de la semana</h1>
        </div>

        <div class="row g-4">
            <?php if (count($listaProductos) > 0) : ?>
                <?php foreach ($listaProductos as $objProducto) :
                    $cantidad = $objProducto->getStockProducto();
                    if ($cantidad > 12) : //acá lo que hice es que para que se muestren algunos productos y no elegirlos a mano, puse la condicion de que los productos con stock mayor a 12 son los que se muestran en el inicio, como para variar lo que se muestre
                        $rutaImagen = $objProducto->getProImagen();
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
                                    <button class="btn btn-info text-white rounded-pill" onclick="agregarAlCarrito(<?php echo $objProducto->getIdProducto(); ?>)">
                                        <!-- acá pngo el boton con la accion que lleva a la funcion de agregarAlCarrito(id) esta funcion está en el action carrito.php -->
                                        <i class="bi bi-cart-plus"></i> Añadir al carrito
                                    </button>
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

    <?php elseif ($nombreRol === "deposito") : ?>
        <!-- si el rol de la persona que tiene la sesion ahora es deposito hago todo el resto -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-info">Stock de productos</h1>
        </div>
        <ul class="list-group shadow-sm">
            <?php foreach ($listaProductos as $objProducto) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-box-seam me-2"></i>
                        <strong><?php echo $objProducto->getNombreProducto(); ?></strong>
                    </div>
                    <span class="badge bg-info p-2">Cant: <?php echo $objProducto->getStockProducto(); ?></span>
                    <!-- lo unico que hago acá es que los de deposito puedan ver la cantidad de stock que hay, solo eso porque ellos creo queno son lo que se tienen que encargar de reponer y tal, ellos solo gestionan las compras ya iniciadas -->
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
    </main>


    <footer class="bg-dark text-white text-center py-3 mt-5" id="main-header">
        <p>&copy; 2026 MascotaFeliz - TPFINAL PWD</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/jquery_ajax.js"></script>
</body>

</html>