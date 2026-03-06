<?php
include_once '../../configuracion.php';
$objSession = new Session();
$nombreRol = $objSession->getRol(); // 'admin', 'cliente' o 'deposito'
$idRol = $objSession->getIdRol();
$usuarioNombre = $objSession->getUsuario();
$usuarioMail = $objSession->getMail(); //esto tengo que ver cómo lo hago porque no sé si voy a tener que guardar el mail en la sesion o ir a buscarlo a la base de datos pero por las dudas dejo algo así
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
                        <h5 class="fw-bold mb-3">Mis Pedidos</h5>
                        <div id="contenedor-pedidos-cliente">
                            <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Hacé clic para ver el estado de tus compras.</div>
                        </div>
                    </div>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1"> <div class="modal-dialog modal-dialog-centered">
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