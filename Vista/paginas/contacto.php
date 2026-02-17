<!DOCTYPE html>
<!-- segun entiendo esto es lo que va a ver CUALQUIER persona indistintamente si tiene cuenta o no, es una paginapublica -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styleEstructura.css">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">MascotaFeliz</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="./productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./contacto.php">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Ingresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="container my-5">
    <h2 class="text-center mb-4">Envianos un mensaje</h2>

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
                <label for="msg" class="form-label">Mensaje:</label>
                <input type="text" class="form-control" id="msg" name="msg" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        </form>
    </main>
    
    <footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2026 MascotaFeliz - TPFINAL PWD</p>

    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="../js/jquery-4.0.0.js"></script>
    <script src="../js/jquery_ajax.js"></script>
</body>