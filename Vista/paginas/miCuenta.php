<?php
include_once '../../configuracion.php';
$objSession = new Session();
$nombreRol = $objSession->getRol(); // 'admin', 'cliente' o 'deposito'
$usuarioNombre = $objSession->getUsuario();
$usuarioMail = isset($_SESSION['usmail']) ? $_SESSION['usmail'] : 'usuario@ejemplo.com'; //esto tengo que ver cómo lo hago porque no sé si voy a tener que guardar el mail en la sesion o ir a buscarlo a la base de datos pero por las dudas dejo algo así
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

                <?php if ($nombreRol === "admin") : ?>
                <li class="nav-item">
                    <button class="nav-link" id="admin-tab" data-bs-toggle="tab" data-bs-target="#admin" type="button">
                        <i class="bi bi-shield-lock"></i> Panel Admin
                    </button>
                </li>
                <?php endif; ?>

                <?php if ($nombreRol === "cliente") : ?>
                <li class="nav-item">
                    <button class="nav-link" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras" type="button">
                        <i class="bi bi-bag-check"></i> Mis Compras
                    </button>
                </li>
                <?php endif; ?>

                <?php if ($nombreRol === "deposito") : ?>
                <li class="nav-item">
                    <button class="nav-link" id="deposito-tab" data-bs-toggle="tab" data-bs-target="#deposito" type="button">
                        <i class="bi bi-box-seam"></i> Operaciones
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
                                <p class="mb-0"><strong>Rol:</strong> <span class="badge bg-primary"><?php echo strtoupper($nombreRol); ?></span></p>
                            </div>
                            <button class="btn btn-primary mt-3" onclick="abrirModalEditar()">
                                <i class="bi bi-pencil-square"></i> Editar Mis Datos
                            </button>
                        </div>
                        <div class="col-md-5 border-start ps-4">
                            <h5 class="fw-bold text-danger"><i class="bi bi-shield-shaded me-2"></i>Seguridad</h5>
                            <button class="btn btn-outline-danger w-100" onclick="abrirModalPassword()">
                                <i class="bi bi-key"></i> Cambiar Contraseña
                            </button>
                        </div>
                    </div>
                </div>

                <?php if ($nombreRol === "admin") : ?>
                <div class="tab-pane fade" id="admin">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card h-100 border-danger border-2 text-center p-3">
                                <i class="bi bi-people fs-1 text-danger"></i>
                                <h6 class="mt-2">Gestión de usuarios</h6>
                                <button class="btn btn-sm btn-danger mt-auto" onclick="mostrarSeccion(9)">Ir a Usuarios</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-dark border-2 text-center p-3">
                                <i class="bi bi-list-nested fs-1 text-dark"></i>
                                <h6 class="mt-2">Configurar Menús</h6>
                                <button class="btn btn-sm btn-dark mt-auto" onclick="mostrarSeccion(11)">Ir a Menús</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-dark border-2 text-center p-3">
                                <i class="bi bi-list-nested fs-1 text-dark"></i>
                                <h6 class="mt-2">Configurar Roles</h6>
                                <button class="btn btn-sm btn-dark mt-auto" onclick="mostrarSeccion(10)">Ir a Roles</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($nombreRol === "cliente") : ?>
                <div class="tab-pane fade" id="compras">
                    <h5 class="fw-bold mb-3">Mis Pedidos</h5>
                    <div id="contenedor-pedidos-cliente">
                        <div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>Hacé clic para ver el estado de tus compras.</div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($nombreRol === "deposito") : ?>
                <div class="tab-pane fade" id="deposito">
                    <div class="row g-3">0
                        <div class="col-md-6">
                            <div class="card bg-warning bg-opacity-10 border-warning p-4">
                                <h5><i class="bi bi-truck me-2"></i>Pedidos Pendientes</h5>
                                <p>Pedidos en despacho.</p>
                                <button class="btn btn-warning" onclick="mostrarSeccion(27)">Gestionar envíos</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-secondary bg-opacity-10 border-secondary p-4">
                                <h5><i class="bi bi-boxes me-2"></i>Inventario</h5>
                                <p>Revisa el stock antes de que se agoten los productos.</p>
                                <button class="btn btn-secondary" onclick="mostrarSeccion(1)">Ver Stock</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarPerfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formActualizarDatos">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar mis Datos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" name="usnombre" class="form-control" value="<?php echo $usuarioNombre; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" name="usmail" class="form-control" value="<?php echo $usuarioMail; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function abrirModalEditar(){

    }

    function abrirModalPassword(){
        
    }
</script>