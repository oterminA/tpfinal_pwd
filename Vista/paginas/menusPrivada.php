<?php
include_once '../../configuracion.php';
$ctrlMenus = new MenuController();
$listaMenus = $ctrlMenus->buscar(null);
$ctrlMR = new MenuRolController();

?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Gestionar información de menús</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoMenu"><i class="bi bi-plus-circle"></i> Nuevo Menu</button>
</div>

<table class="table table-hover shadow-sm bg-white">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripcion</th>
            <th>ID menu padre</th>
            <th>Estado</th>
            <th>Rol asociado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listaMenus as $objMenu) :
            $rolesDelMenu = $ctrlMR->buscar(['idmenu' => $objMenu->getIdMenu()]);
            $estado = ($objMenu->getDeshabilitado() == null || $objMenu->getDeshabilitado() == '0000-00-00 00:00:00')
                ? '<span class="badge bg-success">Activo</span>'
                : '<span class="badge bg-danger">Deshabilitado</span>';
            // esto es para manejar el color del badge segun esté habilitado o no el menu y eso puede saberse por el contenido de $estado y haciendo el ternario: si hay fecha está desactivado, si es null está activado
        ?>
            <tr>
                <td><?php echo $objMenu->getIdMenu(); ?></td>
                <td><?php echo $objMenu->getNombreMenu(); ?></td>
                <td><?php echo $objMenu->getMenuDescripcion(); ?></td>
                <td><?php
                    $objPadre = $objMenu->getObjMenuPadre();
                    echo ($objPadre != null) ? $objPadre->getIdMenu() : '-';
                    ?></td>
                <td><?php echo $estado; ?></td>
                <td>
                    <?php
                    foreach ($rolesDelMenu as $objMR) {
                        echo '<span class="badge border text-dark me-1">' . $objMR->getObjRol()->getRolDescripcion() . '</span>';
                    }
                    ?>
                </td>
                <td class="text-center">
                    <?php
                    $idsRoles = [];
                    foreach ($rolesDelMenu as $objMR) {
                        $idsRoles[] = $objMR->getObjRol()->getIdRol();
                    }
                    $jsonRoles = json_encode($idsRoles);
                    ?>
                    <?php
                    $idPadre = ($objPadre != null) ? $objPadre->getIdMenu() : "";
                    ?>
                    <button class="btn btn-sm btn-warning" onclick='modificarMenu(<?php echo $objMenu->getIdMenu(); ?>, 
    "<?php echo $objMenu->getNombreMenu(); ?>", 
    "<?php echo $objMenu->getMenuDescripcion(); ?>", 
    "<?php echo $idPadre; ?>", 
    <?php echo $jsonRoles; ?>)'>
                        <i class="bi bi-pencil"></i>
                    </button><!-- acá tengo un boton con una accion que es la funcion js de modificarMenu(id) que va a estar en un action gestionada -->
                    <button class="btn btn-sm btn-danger" onclick="eliminarMenu(<?php echo $objMenu->getIdMenu(); ?>)"><i class="bi bi-trash"></i></button>
                    <!-- acá tengo un boton con una accion que es la funcion js de eliminarMenu(id) que va a estar en un action gestionada -->

                    <button class="btn btn-sm btn-success" onclick="habilitarMenu(<?php echo $objMenu->getIdMenu(); ?>)"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <!-- acá tengo un boton con una accion que es la funcion js de habilitarMenu(id) que va a estar en un action gestionada -->
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- modal flotante para la CREACION del menu -->
<div class="modal fade" id="modalNuevoMenu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Menu (Admin)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAltaMenu">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de Menu</label>
                        <input type="text" name="menombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <input type="text" name="medescripcion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID menu padre (opcional)</label>
                        <input type="number" name="idpadre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol asociado (opcional)</label>
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
                    <button type="submit" class="btn btn-primary">Crear Menu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal flotante para la EDICION del menu -->
<div class="modal fade" id="modalEditarMenu" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modificar Menu (ID: <span id="idMenuTitulo"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarMenu">
                <div class="modal-body">
                    <input type="hidden" name="idmenu" id="editar_id">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre menú</label>
                        <input type="text" name="menombre" id="editar_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descripcion (opcional)</label>
                        <input type="text" name="medescripcion" id="editar_descripcion" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">ID menú padre (opcional)</label>
                        <input type="number" name="idpadre" id="editar_idpadre" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold d-block">Rol asignado (opcional)</label>
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
    function modificarMenu(id, nombre, descripcion, idPadre, rolesIds) {
        $("#editar_id").val(id);
        $("#idMenuTitulo").text(id);
        $("#editar_nombre").val(nombre);
        $("#editar_descripcion").val(descripcion);
        $("#editar_idpadre").val(idPadre);

        $(".check-rol").prop("checked", false);

        if (rolesIds && rolesIds.length > 0) {
            rolesIds.forEach(idRol => {
                $(".check-rol[value='" + idRol + "']").prop("checked", true);
            });
        }

        var modal = new bootstrap.Modal(document.getElementById('modalEditarMenu'));
        modal.show();
    }

    //para modificar la info de un menu
    $("#formEditarMenu").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/modificarMenu.php",
            data: $(this).serialize(), //esto es para que todos los id coincidan y no haya problemas con eso
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Menu modificado correctamente"); //alert con el msj de que fue creado el user
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });


    //para dar de alta a un menu
    $("#formAltaMenu").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/altaMenu.php", //ese es el action que se encarga de gestionar el alta en la parte del servidor
            data: $(this).serialize(), //esto es para que todos los id coincidan y no haya problemas con eso
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Menu creado correctamente"); //alert con el msj de que fue creado el user
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });


    function eliminarMenu(id) {
        if (confirm('¿Desea desactivar al menu?')) { //apartir de lo que acepte o no el user es que manejo las cosas, si el user pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/eliminarMenu.php", //el servidor va a manejar los datos a través de este action
                data: {
                    idmenu: id
                },
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Menu desactivado correctamente"); //alert con el msj de que fue desactivado el user
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }

    }

    function habilitarMenu(id) {
        if (confirm('¿Desea activar al menu?')) { //apartir de lo que acepte o no el user es que manejo las cosas, si el user pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/habilitarMenu.php", //action que va a gestionar la habilitacion por parte del servidor
                data: {
                    idmenu: id
                }, //id del user a habilitar
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Menu activado correctamente"); //alert con el msj de que fue activado el user
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }
    }
</script>