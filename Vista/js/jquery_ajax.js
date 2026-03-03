//acá voy a poner las lineas de codigo que tengan que ver con jquery para ajax o algo así
//este script tiene que ir en todos los scripts html, abajo, en el body y después del que tiene el codigo de jquery posta 

///////////////////////
/////index: para todos
///////////////////////
function mostrarSeccionPublica(seccion) { //funcion js que segun lo que entre por parámetros es el archivo/script que corresponde
    let archivo = "";
    switch (seccion) {
        case 'productos':
            archivo = "productos.php";
            break;
        case 'contacto':
            archivo = "contacto.php";
            break;
        default:
            archivo = "index.php";
    }


    $.ajax({
        url: archivo, //lo que se haya guardado en archivo 'index.php' por ejemplo es a lo que se va a redirigir asincronamente
        type: 'GET', //uso get porque no estoy pasando información sensible o algo que no se tenga que mostrar en la url por ejemplo
        success: function(respuesta) { //respuesta si sale todo bien
            $('#contenido-publico').html(respuesta); //o sea si la peticion ajax funciona (que se redirija al menu necesario) se va a ver reflejado en el main (o sea mostrando lo que sea de ese menú por ejemplo si es contacto se muestra el form de contacto)
        }
    });
}



///////////////////////
/////contacto publica y privada
///////////////////////
$(document).ready(function() {
    $("#formContacto").on("submit", function(e) { //cuando se haga click en el boton que tiene el submit es que se va a aplicar esta funcion
        e.preventDefault(); //evita q se recargue la pagina

        $("#mensaje").html('<div class="text-center text-success">¡Mensaje enviado!</div>'); //le pone ese texto al div con id mensaje
        $("#nombre").val(''); //deja en blanco el input con ese id que es el nombre
        $("#email").val(''); //mismo pero con el input de id email
        $("#msg").val(''); //lo mismo pero con el inpud de id msg
    });
});




///////////////////////
/////home: autorizados
///////////////////////

function cerrarSesion() {//funcion js para manejar el cierre de sesiones
    if (confirm('¿Desea cerrar sesión?')) {//apartir de lo que acepte o no el user es que manejo las cosas, si el user pone aceptar:
        $.ajax({
            url: '../Action/cerrarSesion.php', //el action es el que va a manejar las cosas, usando el cerrar de la clase sesion
            type: 'POST', //el tipo de envio es post
            success: function(response) { //si la respuesta es positiva
                window.location.href = 'login.php'; //mando al login
            },
            error: function() { //si algo pasó en el action
                alert("Error al cerrar sesión"); //alert para el user
            }
        });
    }
}

function mostrarSeccion(id) { //funcion js que tiene el swtich para mostrar el conteindo segun el script y el ajax para quetodo sea asincrono
    let archivo = "";
    switch (id) {
        case 1:
            archivo = "productosPrivada.php";
            break; //para cliente
        // case 2:
        //     archivo = "inicioPrivada.php";
        //     break; //para cliente, admin, deposito
        case 3:
            archivo = "contactoPrivada.php";
            break; //para cliente
        case 6:
            archivo = "miCuenta.php";
            break; //para cliente, admin
        case 7:
            archivo = "carritoPrivada.php";
            break; //para cliente
        case 9:
            archivo = "usuariosPrivada.php";
            break; //para admin
        case 10:
            archivo = "rolesPrivada.php";
            break; //para admin
        case 11:
            archivo = "menusPrivada.php";
            break; //para admin
        case 27:
            archivo = "pedidosPrivada.php";
            break; //para deposito
            default: archivo = "home.php";
    }

    // if (archivo === "") {
    //     alert("La sección con ID aún no tiene un script asignado.");
    //     return; //
    // }

    $.ajax({ //codigo ajax
        url: archivo, //la url es el archivo que queda indicado arriba
        type: 'GET', //envio por get porque si
        success: function(respuesta) { 
            $('#contenido-central').html(respuesta); //es lo que muestra 
        },
        error: function() { //por si falla cargar el meu
            $('#contenido-central').html('<div class="alert alert-danger">Error: No se pudo cargar ' + archivo + '</div>'); 
        }
    });
}


///////////////////////
/////login:autorizados
///////////////////////
$('#formLogin').submit(function(e) {
    e.preventDefault();
    
    $.ajax({
        data: $(this).serialize(),
        url: '../Action/verificarLogin.php',
        type: 'POST',
        
        beforeSend: function() {
            $('#mensaje').html('<div class="alert alert-info">Verificando...</div>');
        },
        
        success: function(respuesta) {
            let resultado = JSON.parse(respuesta);
            
            if (resultado.success) {
                $('#mensaje').html('<div class="alert alert-success">Login exitoso. Redirigiendo...</div>');
                
                // Redirigir al panel según rol
                setTimeout(function() {
                    window.location.href = 'home.php';
                }, 1000);
                
            } else {
                $('#mensaje').html('<div class="alert alert-danger">' + resultado.msg + '</div>');
            }
        },
        
        error: function() {
            $('#mensaje').html('<div class="alert alert-danger">Error de conexión</div>');
        }
    });
});



///////////////////////
/////registrarse:cualuqiera
///////////////////////
$(document).ready(function() { //cuando se cargue todo el documento se ejecuta la funcion
    $("#formRegistro").on("submit", function(e) {
            e.preventDefault(); //esto hace que la pagina NO se recargue 

            $("#mensaje").html('<div class="text-center"><div class="spinner-border spinner-border-sm text-info"></div> Procesando...</div>'); //spinner para cuando se está cargando la info

            $.ajax({
                    url: '../Action/altaUsuario.php', //el action que va a manejar los datos, va a hacer las gestiones entre el control y el modelo y demás
                    type: 'POST', //envio del tipo post
                    data: $("#formRegistro").serialize(), //esto es para mandarle los datos correctos al action y no tener problemas con que los id no están bien escritos y eso
                    success: function(response) {
                        let res = JSON.parse(response); //se parsea la respuesta de json
                        if (res.success) { //si salió bien 
                            $("#mensaje").html('<div class="alert alert-success">Éxito. Redirigiendo...</div>');
                            //mensaje de exito
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 2000); // redirijo al login  2 segundos depsues
                    } else { //Si salió mal dice qué es lo que pasó
                        $("#mensaje").html('<div class="alert alert-danger">' + res.msg + '</div>');
                    }
                },
                error: function() { //si no hubo conexion con el servidor
                    $("#mensaje").html('<div class="alert alert-danger">Error de conexión con el servidor.</div>');
                }
            });
    });
});

///////////////////////
/////carritoprivada
///////////////////////
function eliminarDelCarrito(id) { //funcion js con ajax adentro que usa jquery como codigo
    $.ajax({
        url: '../Action/carrito.php', //el action q va a manejar el control, el modelo y las respuestas a lo que ve el user
        type: 'POST', //tipo de envio, en este caso es post
        data: { idproducto: id, accion: 'eliminar' }, //son los datos que se le mandan al action, el idque tengo que eleiminar y qué accion es porque en el action yo uso switch por la cantidad de funcones que voy a manejar así no hago muchos actions
        success: function(response) {  //lo que se ejecuta cuando php responde
            let res = JSON.parse(response); //lo que respondió el php que está en json se convierte en algo que js maneja
            if (res.success) { //si se eliminó bien el producto
                mostrarSeccion(7); //se recarga esta parte que es lo que está en el home.php y corresponde a este propio script
            }
        }
    });
}

function actualizarCantidad(id, cantidad) {//funcion js con ajax adentro que usa jquery como codigo
    $.ajax({
        url: '../Action/carrito.php', //el action q va a manejar el control, el modelo y las respuestas a lo que ve el user
        type: 'POST',//tipo de envio, en este caso es post
        data: { idproducto: id, accion: 'actualizar' }, //son los datos que se le mandan al action, el idque tengo que actualizar su cantidad y qué accion tengo que poner en el case del switch
        success: function(response) { //lo q se ejecuta cuando php responde
            let res = JSON.parse(response); //lo que respondió el php que está en json y se convierte en algo que js maneja
            if (res.success) { //si se actualizó nien el producto
                mostrarSeccion(7); //se recarga esta parte  que es lo que está en el home.php y corresponde a este propio script
            }
        }
    });
}

function finalizarCompra() {//funcion js con ajax adentro que usa jquery como codigo
    $.ajax({
        url: '../Action/carrito.php', //el action q va a manejar el control, el modelo y las respuestas a lo que ve el user
        type: 'POST',//tipo de envio, en este caso es post
        data: { accion: 'finalizar' }, // qué accion tengo que poner en el case del switch
        success: function(response) {
            let res = JSON.parse(response); //lo que respondió el php que está en json y se convierte en algo que js maneja
            if (res.success) { //si se actualizó nien el producto
                alert("Compra realizada con éxito"); //alert para el user
                mostrarSeccion(7); //se recarga esta parte  que es lo que está en el home.php y corresponde a este propio script
            }
        }
    });

    function agregarAlCarrito(idProducto) {//funcion js con ajax adentro que usa jquery como codigo
        $.ajax({
            url: '../Action/carrito.php',//el action q va a manejar el control, el modelo y las respuestas a lo que ve el user
            type: 'POST',//tipo de envio, en este caso es post
            data: { // qué accion tengo que poner en el case del switch y que id voy a agregar
                idproducto: idProducto,
                accion: 'agregar'
            },
            success: function(response) {//lo que respondió el php que está en json y se convierte en algo que js maneja
                let res = JSON.parse(response);//lo que respondió el php que está en json y se convierte en algo que js maneja
                if (res.success) {//si se actualizó nien el producto
                    alert("Producto añadido al carrito"); //alert para el user
                } else {
                    alert("Error al añadir el producto al carritod"); //alert para el user por si no salió bien
                }
            }
        });
    }
}


///////////////////////
/////home pero el nav de rol
///////////////////////
$(".btn-cambiar-rol").on("click", function(e) {
    e.preventDefault();
    let idRol = $(this).data("rol");

    $.ajax({
        type: "POST",
        url: "../Action/elegirRol.php",
        data: { idrol: idRol },
        success: function(response) {
            let res = JSON.parse(response);
            if (res.success) {
                window.location.href = "home.php";
            } else {
                alert(res.msg);
            }
        }
    });
});