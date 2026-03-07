<?php
include_once '../../configuracion.php';
$objSession = new Session();
$nombreRol = $objSession->getRol(); // 'admin', 'cliente' o 'deposito'
$idRol = $objSession->getIdRol();
$usuarioNombre = $objSession->getUsuario();
$usuarioMail = $objSession->getMail();

////esto es para poder mostrar las compras de cada user
$idUsuarioLogueado = $objSession->getIdUsuario(); //recupero el id del usuario q esta en la sesion
$objCtrlCompraItem = new CompraItemController();
$objCtrlCompraEstado = new CompraEstadoController();
$objCtrlCompra = new CompraController();
$todasMisCompras = $objCtrlCompra->buscar(['idusuario' => $idUsuarioLogueado]);
?>

<div class="container mt-4 animate__animated animate__fadeIn">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-4">
                <i class="bi bi-person-circle fs-1 me-3 text-primary"></i>
                <div>
                    <h2 class="mb-0">Mi Cuenta</h2>
                    <p class="text-muted mb-0">Gestioná tu información.</p>
                </div>
            </div>

            <ul class="nav nav-tabs custom-tabs" id="cuentaTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="perfil-tab" data-bs-toggle="tab" data-bs-target="#perfil" type="button">
                        <i class="bi bi-person-vcard"></i> Mis Datos
                    </button>
                </li>

                <?php if ($idRol === 1) : ?>
                    <li class="nav-item">
                        <button class="nav-link" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras" type="button">
                            <i class="bi bi-bag-check"></i> Mis Compras
                        </button>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="tab-content border border-top-0 p-4 bg-white shadow-sm rounded-bottom">

                <div class="tab-pane fade show active" id="perfil">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Información Personal</h5>
                            <div class="card bg-light border-0 p-3 mt-3">
                                <p class="mb-2"><strong>Nombre de Usuario:</strong> <span id="txt-nombre"><?php echo $usuarioNombre; ?></span></p>
                                <p class="mb-2"><strong>Correo electrónico:</strong> <span id="txt-mail"><?php echo $usuarioMail; ?></span></p>
                                <p class="mb-0"><strong>Rol o roles asignados:</strong>
                                    <?php
                                    $misRoles = $objSession->getRol();
                                    if (!empty($misRoles)) {
                                        foreach ($misRoles as $unRol) {
                                            echo '<span class="badge bg-primary me-1">' . strtoupper($unRol->getRolDescripcion()) . '</span>';
                                        }
                                    } else {
                                        echo '<span class="text-muted">Sin roles asignados</span>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <button class="btn btn-primary mt-3" onclick="modificarUsuario(
    '<?php echo $objSession->getIdUsuario(); ?>', 
    '<?php echo $usuarioNombre; ?>', 
    '<?php echo $usuarioMail; ?>'
)">
                                <i class="bi bi-pencil-square"></i> Editar Mis Datos
                            </button>
                        </div>
                    </div>
                </div>

                <?php if ($idRol === 1) : ?>
                    <div class="tab-pane fade" id="compras">
    <h5 class="fw-bold mb-4"><i class="bi bi-clock-history me-2"></i>Seguimiento de mis pedidos</h5>
    <div class="row g-4">
        <?php if (count($todasMisCompras) > 0) : ?>
            <?php foreach ($todasMisCompras as $objCompra) : 
                $idC = $objCompra->getIdCompra();
                // Buscamos TODOS los estados por los que pasó esta compra, ordenados por fecha
                $historialEstados = $objCtrlCompraEstado->buscar(['idcompra' => $idC]);
                $items = $objCtrlCompraItem->buscar(['idcompra' => $idC]);
            ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 mb-0">Pedido nro:<?php echo $idC; ?></span>
                                <small class="text-muted">Iniciado el: <?php echo $objCompra->getFecha(); ?></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 border-end">
                                    <p class="fw-bold mb-2 small text-uppercase text-muted">Productos</p>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($items as $item) : ?>
                                            <li class="small mb-1">
                                                <i class="bi bi-dot"></i> 
                                                <?php echo $item->getObjProducto()->getNombreProducto(); ?> 
                                                <span class="fw-bold">(x<?php echo $item->getCantidad(); ?>)</span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="col-md-8 px-4">
                                    <p class="fw-bold mb-3 small text-uppercase text-muted">Estado del envío</p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php foreach ($historialEstados as $index => $objCE) : 
                                            $esUltimo = ($index === count($historialEstados) - 1);
                                            $tipoEstado = $objCE->getObjCet()->getIdCompraEstadoTipo();
                                            $nombreEstado = $objCE->getObjCet()->getDescripcion();
                                            
                                            $clase = $esUltimo ? "btn-primary" : "btn-outline-secondary opacity-50";
                                        ?>
                                            <div class="d-flex align-items-center">
                                                <span <?php echo $clase; ?>>
                                                    <?php if ($esUltimo)  ?>
                                                    <?php echo strtoupper($nombreEstado); ?>
                                                </span>
                                                <?php if (!$esUltimo) : ?>
                                                    <i class="bi bi-chevron-right mx-1 text-muted"></i>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <?php 
                                    $estadoActual = end($historialEstados);
                                    if ($estadoActual->getObjCet()->getIdCompraEstadoTipo() == 2) : ?>
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-sm btn-link text-danger p-0" onclick="cancelarCompra(<?php echo $idC; ?>)">
                                                ¿Querés cancelar este pedido?
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No tenés ninguna compra registrada.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEditarUsuario">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar mis Datos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idusuario" id="editar_id">

                    <div class="mb-3">
                        <label class="form-label">Nombre de usuario (opcional)</label>
                        <input type="text" name="usnombre" id="editar_nombre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico (opcional)</label>
                        <input type="email" name="usmail" id="editar_mail" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña (opcional)</label>
                        <input type="password" name="uspass" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function modificarUsuario(id, nombre, mail) {
        $("#editar_id").val(id);
        $("#editar_nombre").val(nombre);
        $("#editar_mail").val(mail);
        $("#editar_pass").val("");
        var myModal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
        myModal.show();
    }

    $("#formEditarUsuario").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "../Action/modificarUsuario.php",
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
                let res = JSON.parse(response);
                if (res.success) {
                    alert(res.msg);
                    location.reload();
                } else {
                    alert("Error");
                }
            },
            error: function() {
                alert("Error crítico en el servidor.");
            }
        });
    });