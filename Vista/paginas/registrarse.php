<!DOCTYPE html>
<!-- ESTE SCRIPT ESTÁ EN LA VISTA PUBLICA -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - MascotaFeliz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h4 class="text-center mb-4">Crear cuenta</h4>

        <form id="formRegistro">
            <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usnombre" id="usnombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="usmail" id="usmail" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="uspass" id="uspass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary text-white w-100 mt-2">Registrarse</button>
        </form>

        <div id="mensaje" class="mt-3"></div>
        <!-- para el mensaje de error o login exitosos -->
        <hr>
        <p class="text-center mb-0">¿Tenés cuenta?</p>
        <a href="../paginas/login.php" class="btn btn-outline-secondary w-100 mt-2">Iniciar sesión</a>
        <a href="../paginas/index.php" class="btn btn-link w-100 mt-2">Volver a MascotaFeliz</a>
        <!-- acá no uso ajax porque no necesito que se muestre un contenido distinto estando en la misma pagina, tiene que salirse -->
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery_ajax.js"></script>
</body>

</html>