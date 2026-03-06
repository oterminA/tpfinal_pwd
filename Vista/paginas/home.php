<?php
//ESTE SCRIPT ES PARA LA VISTA PRIVADA, SE ACCEDE CON AUTENTICACIÓN DE CREDENCIALES
// acá yo armaría el menú dinamico, cada user va a ver esto ajustado a su rol, el index.php es para todos
include_once '../../configuracion.php';
$objSession = new Session(); // hago un new de session
if (!$objSession->validar()) { //si se puede validar la sesión se continua y sino se redirije al login para que se ingrese correctamente
    header('Location: login.php');
    exit;
}
$rolCtrl = new RolController(); //new de la clase rol
$menuCtrl = new MenuController(); //new de la clase menu
$menuRolCtrl = new MenuRolController(); //new de la clase menu rol que es la que maneja la relacion de qué menu se ve segun el rol
$ctrlProductos = new ProductoController(); //hago un new de productos


$nombreUsuario = $objSession->getUsuario(); //recupero el usuario que está en la sesión
if (!isset($_SESSION['rol_activo'])) { //si no es vacio
    $nombreRolPrincipal = $objSession->getRol(); //recupero el nombre del rol que esta en la sesion
    $_SESSION['rol_activo'] = $rolCtrl->obtenerRol($nombreRolPrincipal); //acá obtengo el id del rol de arribay lo guardo como el rol activo
    $_SESSION['rol_nombre_activo'] = $nombreRolPrincipal; //el nombre del rol principal, el de la sesion, lo guardo como el nombre del rol activo así tengo las dos cosas por las dudas
}
$idRolActivo = $_SESSION['rol_activo'];
$nombreRolActivo = $_SESSION['rol_nombre_activo'];

$listaMenuRol = $menuRolCtrl->buscar(['idrol' => $idRolActivo]);
$listaProductos = $ctrlProductos->buscar(null); //traigo todos los productos que esten guardados como tal

$nombreRol = $objSession->getRol(); //traigo el nombre del rol del usuario que tiene la sesión activa AHORA por ejemplo si el user es 'caro' el rol es 'admin'

$idRol = $rolCtrl->obtenerRol($nombreRol); //obtengo el id del rol desde la clase rol
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
                        if (is_array($listaMenuRol)) {
                            foreach ($listaMenuRol as $item) {
                                $objMenu = $item->getObjMenu();
                                if ($objMenu->getDeshabilitado() == null) {
                                    echo '<li class="nav-item">';
                                    echo '  <a class="nav-link" href="javascript:void(0)" onclick="mostrarSeccion(' . $objMenu->getIdMenu() . ')">' . $objMenu->getNombreMenu() . '</a>';
                                    echo '</li>';
                                }
                            }
                        }
                        ?>
                        <li class="nav-item">
                            <button class="nav-link text-warning" onclick="cerrarSesion()"> Salir </button>
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
                <strong>¡Hola <?php echo $nombreUsuario; ?>!</strong>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Cambiar Vista
                </button>
                <ul class="dropdown-menu">
                    <?php
                    foreach ($_SESSION['roles_poseidos'] as $itemRol) {
                        $objRol = $itemRol->getObjRol();
                        $claseActivo = ($objRol->getIdRol() == $idRolActivo) ? 'active' : '';
                        echo '<li><a class="dropdown-item btn-cambiar-rol ' . $claseActivo . '" href="#" data-rol="' . $objRol->getIdRol() . '">' . $objRol->getRolDescripcion() . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="container my-5">

            <?php if ($idRolActivo == 3) : ?>
                <!-- si el rol de la persona que tiene la sesion ahora es admin hago todo el resto -->
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-info">Inventario de productos</h1>
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                        <i class="bi bi-plus-circle"></i> Nuevo Producto
                    </button>
                </div>
                <table class="table table-hover shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Imagen</th>
                            <th>Estado</th>
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
                                <td><?php echo $objProducto->getProImagen(); ?></td>
                                <td>
                                    <?php
                                    $estado = ($objProducto->getProductoDeshabilitado() == null || $objProducto->getProductoDeshabilitado() == '0000-00-00 00:00:00')
                                        ? '<span class="badge bg-success">Activo</span>'
                                        : '<span class="badge bg-danger">Deshabilitado</span>';
                                    echo $estado; // esto es para manejar el color del badge segun esté habilitado o no el rol y eso puede saberse por el contenido de $estado y haciendo el ternario: si hay fecha está desactivado, si es null está activado
                                    ?>
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning" onclick="modificarProducto(
    '<?php echo $objProducto->getIdProducto(); ?>', 
    '<?php echo $objProducto->getNombreProducto(); ?>', 
    '<?php echo $objProducto->getDetalleProducto(); ?>', 
    '<?php echo $objProducto->getStockProducto(); ?>', 
    '<?php echo $objProducto->getPrecioProducto(); ?>', 
    '<?php echo $objProducto->getProImagen(); ?>'
)">
                                        <i class="bi bi-pencil"></i>
                                    </button>
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

            <?php elseif ($idRolActivo == 1) : ?>
                <!-- si el rol de la persona que tiene la sesion ahora es cleinte hago todo el resto -->
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-info">Novedades de la semana</h1>
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

            <?php elseif ($idRolActivo == 2) : ?>
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

    <!-- modal flotante para la CREACION de productos -->
    <div class="modal fade" id="modalNuevoProducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Registrar producto (Admin)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formAltaProducto" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del producto</label>
                            <input type="text" name="pronombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <input type="text" name="prodetalle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad de stock</label>
                            <input type="number" name="procantstock" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio</label>
                            <input type="number" name="proprecio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="proimagen" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- modal flotante para la EDICION de productos -->
    <div class="modal fade" id="modalEditarProducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Modificar Producto (ID: <span id="idProductoTitulo"></span>)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarProducto" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="idproducto" id="editar_id">

                        <div class="mb-3">
                            <label class="form-label">Nombre del producto (opcional)</label>
                            <input type="text" name="pronombre" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción (opcional)</label>
                            <input type="text" name="prodetalle" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad de stock (opcional)</label>
                            <input type="number" name="procantstock" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio (opcional)</label>
                            <input type="number" name="proprecio" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen (opcional)</label>
                            <input type="file" name="proimagen" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning shadow-sm">Guardar cambios</button>
                    </div>
            </div>

            </form>
        </div>
    </div>
    </div>


    <footer class="bg-dark text-white text-center py-3 mt-5" id="main-header">
        <p>&copy; 2026 MascotaFeliz - TPFINAL PWD</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery_ajax.js"></script>
</body>

</html>






<script>
    function modificarProducto(id, nombre, detalle, stock, precio, imagen) {
        $("#editar_id").val(id);
        $("#idProductoTitulo").text(id);
        $("input[name='pronombre']").val(nombre);
        $("input[name='prodetalle']").val(detalle);
        $("input[name='procantstock']").val(stock);
        $("input[name='proprecio']").val(precio);

        var modal = new bootstrap.Modal(document.getElementById('modalEditarProducto'));
        modal.show();
    }

    //para modificar la info de un menu
    $("#formEditarProducto").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        var formData = new FormData(this); //captura TODO el formulario, incluyendo los archivos binarios x esto de q ahora estoy agregando la imagen
        formData.append('accion', 'modificar');
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/abmProductos.php",
            data: formData,
            processData: false, //esto hace que jsquery transforme la data
            contentType: false,
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Producto modificado correctamente"); //alert con el msj de que fue creado el user
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });


    //para dar de alta a un menu
    $("#formAltaProducto").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        var formData = new FormData(this); //captura TODO el formulario, incluyendo los archivos binarios x esto de q ahora estoy agregando la imagen
        formData.append('accion', 'alta');

        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/abmProductos.php", //ese es el action que se encarga de gestionar el alta en la parte del servidor
            data: formData,
            processData: false, //esto hace que jsquery transforme la data
            contentType: false,
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Producto creado correctamente"); //alert con el msj de que fue creado el user
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });


    function eliminarProducto(id) {
        if (confirm('¿Desea desactivar el producto?')) {
            $.ajax({
                type: "POST",
                url: "../Action/abmProductos.php",
                data: {
                    idproducto: id,
                    accion: 'eliminar'
                },
                success: function(response) {
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Producto desactivado correctamente"); //alert con el msj de que fue desactivado el user
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }

    }

    function habilitarProducto(id) {
        if (confirm('¿Desea activar el producto?')) { //apartir de lo que acepte o no el user es que manejo las cosas, si el user pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/abmProductos.php", //action que va a gestionar la habilitacion por parte del servidor
                data: {
                    idproducto: id,
                    accion: 'habilitar'
                },
                //id del user a habilitar
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Producto activado correctamente"); //alert con el msj de que fue activado el user
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }
    }
</script>