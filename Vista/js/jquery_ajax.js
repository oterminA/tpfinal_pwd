//acá voy a poner las lineas de codigo que tengan que ver con jquery para ajax o algo así
//este script tiene que ir en todos los scripts html, abajo, en el body y después del que tiene el codigo de jquery posta 

///////////////////////
/////index: para todos
///////////////////////

function agregarTab(titulo, url) {
    if ($('#tabs').tabs('exists', titulo)) {
        $('#tabs').tabs('select', titulo);
    } else {
        $('#tabs').tabs('add', {
            title: titulo,
            href: url,
            closable: true
        });
    }
}



///////////////////////
/////home: autorizados
///////////////////////
function cerrarSesion() {
    if (confirm('¿Desea cerrar sesión?')) {
        window.location.href = '../Action/cerrarSesion.php';
    }
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
$('#formRegistro').submit(function(e) {
    e.preventDefault();
    
    // Validar que las contraseñas coincidan
    if ($('#password').val() !== $('#password2').val()) {
        $('#mensaje').html('<div class="alert alert-danger">Las contraseñas no coinciden</div>');
        return;
    }
    
    $.ajax({
        data: $(this).serialize(),
        url: '../Action/registrarUsuario.php',
        type: 'POST',
        
        beforeSend: function() {
            $('#mensaje').html('<div class="alert alert-info">Registrando...</div>');
        },
        
        success: function(respuesta) {
            let resultado = JSON.parse(respuesta);
            
            if (resultado.success) {
                $('#mensaje').html('<div class="alert alert-success">Cuenta creada. Redirigiendo al login...</div>');
                
                setTimeout(function() {
                    window.location.href = '../paginas/login.php';
                }, 2000);
                
            } else {
                $('#mensaje').html('<div class="alert alert-danger"> ' + resultado.msg + '</div>');
            }
        },
        
        error: function() {
            $('#mensaje').html('<div class="alert alert-danger">Error de conexión</div>');
        }
    });
});

///////////////////////
/////contacto: para todos
///////////////////////