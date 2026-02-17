<?php
session_start();

// Si ya está logueado, redirigir al panel
if (isset($_SESSION['usuario'])) {
    header('Location: ../paginas/home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MascotaFeliz</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styleEstructura.css">
</head>
<body>
    
    <div class="register-container">
        <h2 class="text-center mb-4">Crear Cuenta</h2>
        
        <form id="formRegistro">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
        
        <div id="mensaje" class="mt-3"></div>
        
        <hr class="my-4">
        
        <p class="text-center mb-0">¿Ya tenés cuenta?</p>
        <a href="login.php" class="btn btn-outline-secondary w-100 mt-2">Iniciar sesión</a>
    </div>

    <!-- jQuery -->
    <script src="../js/jquery-4_0_0.js"></script>
    <script src="../js/jquery_ajax.js"></script>
</body>
</html>