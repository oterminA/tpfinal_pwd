<!DOCTYPE html>
<!-- ESTE SCRIPT ES PARA LA VISTA PUBLICA Y PRIVADA PORQUE LOS QUE NO TIENEN USUARIO PUEDEN INGRESAR A ESTE SCRIPT Y REGISTRARSE, MIENTRAS QUE LOS QUE SI TIENEN USUARIO PUEDEN INGRESAR A ESTE SCRIPT A LOGUEARSE -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <h4 class="text-center mb-4">Iniciar sesión</h4>
        
        <form id="formLogin">
            <div class="mb-3">
                <label for="usnombre" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="user" name="usnombre" required>
            </div>
            
            <div class="mb-3">
                <label for="uspass" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="uspass" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
        </form>
        
        <div id="mensaje" class="mt-3"></div>
        
        <hr class="my-4">
        
        <p class="text-center mb-0">¿No tenés cuenta?</p>
        <a href="../paginas/registrarse.php" class="btn btn-outline-secondary w-100 mt-2">Registrarse</a>
        <a href="../paginas/index.php" class="btn btn-link w-100 mt-2">Volver a MascotaFeliz</a>
                <!-- acá no uso ajax porque tengo que salir de la pagina en la que estoy, o sea uso ajax cuando quiero que visualizar otro contenido dentro de la misma pagina sea sin recargar y de manera asincrona, pero acá tengo que dejar de ver el contenido que hayantes  -->
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery_ajax.js"></script> 
    <!-- aca en ese jquery está el ajax para la parte de login, para mostrar mensajes por si está mal laautenticacion -->
</body>
</html>