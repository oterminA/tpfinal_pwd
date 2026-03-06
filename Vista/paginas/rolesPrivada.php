<?php
include_once '../../configuracion.php';
$ctrlRol = new RolController();
$listaRoles = $ctrlRol->buscar(null);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Gestionar información de roles</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNuevoRol"><i class="bi bi-plus-circle"></i></i> Nuevo Rol</button>
</div>

<table class="table table-hover shadow-sm bg-white">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Descripcion</th>
            <th>Estado</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php foreach ($listaRoles as $objRol) :
                $estado = ($objRol->getDeshabilitado() == null || $objRol->getDeshabilitado() == '0000-00-00 00:00:00')
                    ? '<span class="badge bg-success">Activo</span>'
                    : '<span class="badge bg-danger">Deshabilitado</span>';
                // esto es para manejar el color del badge segun esté habilitado o no el rol y eso puede saberse por el contenido de $estado y haciendo el ternario: si hay fecha está desactivado, si es null está activado
            ?>
                <td><?php echo $objRol->getIdRol(); ?></td>
                <td><?php echo $objRol->getRolDescripcion(); ?></td>
                <td><?php echo $estado; ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-warning" onclick="modificarRol(<?php echo $objRol->getIdRol(); ?>, '<?php echo $objRol->getRolDescripcion(); ?>')">
                        <i class="bi bi-pencil"></i>
                    </button><!-- acá tengo un boton con una accion que es la funcion js de modificarRol(id) que va a estar en un action gestionada -->
                    <button class="btn btn-sm btn-danger" onclick="eliminarRol(<?php echo $objRol->getIdRol(); ?>)"><i class="bi bi-trash"></i></button>
                    <!-- acá tengo un boton con una accion que es la funcion js de eliminarRol(id) que va a estar en un action gestionada -->

                    <button class="btn btn-sm btn-success" onclick="habilitarRol(<?php echo $objRol->getIdRol(); ?>)"><i class="bi bi-arrow-counterclockwise"></i></button>
                    <!-- acá tengo un boton con una accion que es la funcion js de habilitarRol(id) que va a estar en un action gestionada -->
                </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<!-- modal flotante para la CREACION del rol -->
<div class="modal fade" id="modalNuevoRol" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Registrar Rol (Admin)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAltaRol">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Descripcion de Rol</label>
                        <input type="text" name="roldescripcion" id="roldescripcion" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Crear Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal flotante para la EDICION del rol -->
<div class="modal fade" id="modalEditarRol" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modificar Rol (ID: <span id="idRolTitulo"></span>)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarRol">
                <div class="modal-body">
                    <input type="hidden" name="idrol" id="editar_id">
                    <div class="mb-3">
                        <label class="form-label">Nueva Descripción</label>
                        <input type="text" name="roldescripcion" id="editar_descripcion" class="form-control" required>
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
    function modificarRol(id, nombre) {
        $("#editar_id").val(id);
        $("#idRolTitulo").text(id);
        $("#editar_descripcion").val(nombre);

        var modal = new bootstrap.Modal(document.getElementById('modalEditarRol'));
        modal.show();
    }

    //para modificar la info de un rol
    $("#formEditarRol").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/modificarRol.php",
            data: $(this).serialize(), //esto es para que todos los id coincidan y no haya problemas con eso
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Rol modificado correctamente"); //alert con el msj de que fue creado el rol
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });


    //para dar de alta a un rol
    $("#formAltaRol").on("submit", function(e) { //acá tomo al elemento directamente por su id
        e.preventDefault(); //evito que se recargue la pagina
        $.ajax({ //parte de ajax
            type: "POST", //envio por POST
            url: "../Action/altaRol.php", //ese es el action que se encarga de gestionar el alta en la parte del servidor
            data: $(this).serialize(), //esto es para que todos los id coincidan y no haya problemas con eso
            success: function(response) { //como manejar la respuesta
                let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                if (res.success) { //si es positiva
                    alert("Rol creado correctamente"); //alert con el msj de que fue creado el rol
                    location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                } else {
                    alert("Error"); //si la respuesta del servidor fue negativa
                }
            }
        });
    });



    function eliminarRol(id) {
        if (confirm('¿Desea desactivar el rol?')) { //apartir de lo que acepte o no el rol es que manejo las cosas, si el rol pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/eliminarRol.php", //el servidor va a manejar los datos a través de este action
                data: {
                    idrol: id
                },
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Rol desactivado correctamente"); //alert con el msj de que fue desactivado el rol
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }

    }

    function habilitarRol(id) {
        if (confirm('¿Desea activar al rol?')) { //apartir de lo que acepte o no el rol es que manejo las cosas, si el rol pone aceptar:
            $.ajax({ //parte de ajax
                type: "POST", //envio por POST
                url: "../Action/habilitarRol.php", //action que va a gestionar la habilitacion por parte del servidor
                data: {
                    idrol: id
                }, //id del rol a habilitar
                success: function(response) { //como manejar la respuesta
                    let res = JSON.parse(response); //parseo la respuesta qe viene del servidor
                    if (res.success) { //si es positiva
                        alert("Rol activado correctamente"); //alert con el msj de que fue activado el rol
                        location.reload(); //estoe s para que se recargue la pagina y muestre actualizada la lista
                    } else {
                        alert("Error"); //si la respuesta del servidor fue negativa
                    }
                }
            });
        }
    }
</script>