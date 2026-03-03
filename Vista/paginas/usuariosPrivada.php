<?php
include_once '../../configuracion.php';
$ctrlUsuarios = new UsuarioController();
$listaUsuarios = $ctrlUsuarios->buscar(null);
$ctrlUR = new UsuarioRolController();
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Gestionar información de usuarios</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario"><i class="bi bi-person-plus"></i> Nuevo Usuario</button>
</div>

<table class="table table-hover shadow-sm bg-white">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Estado</th>
            <th>Roles</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listaUsuarios as $objUsuario) :
            $rolesDelUsuario = $ctrlUR->buscar(['idusuario' => $objUsuario->getIdUsuario()]);
            $estado = ($objUsuario->getDeshabilitado() == null || $objUsuario->getDeshabilitado() == '0000-00-00 00:00:00')
                ? '<span class="badge bg-success">Activo</span>'
                : '<span class="badge bg-danger">Deshabilitado</span>';
            // esto es para manejar el color del badge segun esté habilitado o no el usuario y eso puede saberse por el contenido de $estado y haciendo el ternario: si hay fecha está desactivado, si es null está activado
        ?>
            <tr>
                <td><?php echo $objUsuario->getIdUsuario(); ?></td>
                <td><?php echo $objUsuario->getNombre(); ?></td>
                <td><?php echo $objUsuario->getMail(); ?></td>
                <td><?php echo $estado; ?></td>
                <td>
                    <?php
                    foreach ($rolesDelUsuario as $objUR) {
                        echo '<span class="badge border text-dark me-1">' . $objUR->getObjRol()->getRolDescripcion() . '</span>';
                    }
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    $idsRoles = [];
                    foreach ($rolesDelUsuario as $objUR) {
                        $idsRoles[] = $objUR->getObjRol()->getIdRol();
                    }
                    $jsonRoles = json_encode($idsRoles);
                    ?>
                    <button class="btn btn-sm btn-warning" onclick='modificarUsuario(<?php echo $objUsuario->getIdUsuario(); ?>, "<?php echo $objUsuario->getNombre(); ?>", "<?php echo $objUsuario->getMail(); ?>", <?php echo $jsonRoles; ?>)'>
                        <i class="bi bi-pencil"></i>
                    </button><!-- acá tengo un boton con una accion que es la funcion js de modificarUsuario(id) que va a estar en un action gestionada -->
                    <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(<?php echo $objUsuario->getIdUsuario(); ?>)"><i class="bi bi-trash"></i></button>
                    <!-- acá tengo un boton con una accion que es la funcion js de eliminarUsuario(id) que va a estar en un action gestionada -->

                    <button class="btn btn-sm btn-success" onclick="habilitarUsuario(<?php echo $objUsuario->getIdUsuario(); ?>)"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <!-- acá tengo un boton con una accion que es la funcion js de habilitarUsuario(id) que va a estar en un action gestionada -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- modal flotante para la CREACION del usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Usuario (Admin)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAltaAdmin">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" name="usnombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="usmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="uspass" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol Inicial</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-rol" type="checkbox" name="roles[]" id="rol_1" value="1">
                                <label class="form-check-label" for="rol_1">Cliente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-rol" type="checkbox" name="roles[]" id="rol_2" value="2">
                                <label class="form-check-label" for="rol_2">Depósito</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-rol" type="checkbox" name="roles[]" id="rol_3" value="3">
                                <label class="form-check-label" for="rol_3">Admin</label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal flotante para la EDICION del usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modificar Usuario (ID: <span id="idUsuarioTitulo"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarAdmin">
                <div class="modal-body">
                    <input type="hidden" name="idusuario" id="editar_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre de usuario</label>
                        <input type="text" name="usnombre" id="editar_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="usmail" id="editar_mail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">Nueva contraseña (opcional)</label>
                        <input type="password" name="uspass" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Roles asignados</label>
                        <div id="contenedorRolesEditar" class="p-3 border rounded bg-light">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-rol" type="checkbox" name="roles[]" id="rol_1" value="1">
                                <label class="form-check-label" for="rol_1">Cliente</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-rol" type="checkbox" name="roles[]" id="rol_2" value="2">
                                <label class="form-check-label" for="rol_2">Depósito</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input check-rol" type="checkbox" name="roles[]" id="rol_3" value="3">
                                <label class="form-check-label" for="rol_3">Admin</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning shadow-sm">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function modificarUsuario(id, nombre, mail, rolesIds) {
        $("#editar_id").val(id);
        $("#idUsuarioTitulo").text(id);
        $("#editar_nombre").val(nombre);
        $("#editar_mail").val(mail);
        $("input[name='uspass']").val("");

        $(".check-rol").prop("checked", false);

        if (rolesIds && rolesIds.length > 0) {
            rolesIds.forEach(idRol => {
                $(".check-rol[value='" + idRol + "']").prop("checked", true);
            });
        }

        var modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
        modal.show();
    }

    //para dar de alta a un usuario
    $("#formAltaAdmin").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/altaUsuario.php", //ese es el action que se encarga de gestionar el alta en la parte del servidor
            data: $(this).serialize(), //esto es para que todos los id coincidan y no haya problemas con eso
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Usuario creado correctamente"); //alert con el msj de que fue creado el user
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });

    //para modificar la info de un usuario
    $("#formEditarAdmin").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/modificarLogin.php",
            data: $(this).serialize(), //esto es para que todos los id coincidan y no haya problemas con eso
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Usuario modificado correctamente"); //alert con el msj de que fue creado el user
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });

    function eliminarUsuario(id) {
        if (confirm('¿Desea desactivar al usuario?')) { //apartir de lo que acepte o no el user es que manejo las cosas, si el user pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/eliminarLogin.php", //el servidor va a manejar los datos a través de este action
                data: {
                    idusuario: id
                },
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Usuario desactivado correctamente"); //alert con el msj de que fue desactivado el user
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }

    }

    function habilitarUsuario(id) {
        if (confirm('¿Desea activar al usuario?')) { //apartir de lo que acepte o no el user es que manejo las cosas, si el user pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/habilitarLogin.php", //action que va a gestionar la habilitacion por parte del servidor
                data: {
                    idusuario: id
                }, //id del user a habilitar
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Usuario activado correctamente"); //alert con el msj de que fue activado el user
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }
    }
</script>