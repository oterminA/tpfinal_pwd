<!DOCTYPE html>
<!-- segun entiendo esto es lo que va a ver CUALQUIER persona indistintamente si tiene cuenta o no, es una paginapublica -->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MascotaFeliz</title>
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
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./productos.php">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./contacto.php">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./login.php">Ingresar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="container my-5">
        <div class="text-center">
            <h1>Dale un gusto a tu mascota y regalale lo mejor</h1>
            <h3>Accesorios, ropa, juguetes... todo lo que buscas en un mismo lugar</h3>
        </div>
        
        <div class="row mt-5">
        <div class="text-center">
            <h4>Productos destacados</h4>
        </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="../css/Assets/corgi_juguete.jpg" class="card-img-top" alt="Juguete para perro">
                    <div class="card-body">
                        <h5 class="card-title">Juguete para perros</h5>
                        <p class="card-text">$2500</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="../css/Assets/alimento_perro.jpg" class="card-img-top" alt="Alimento para perro">
                    <div class="card-body">
                        <h5 class="card-title">Alimento para perros adultos por kilo</h5>
                        <p class="card-text">$3000</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="../css/Assets/plato_mascota.jpg" class="card-img-top" alt="Plato de comida">
                    <div class="card-body">
                        <h5 class="card-title">Plato de comida/agua para mascota</h5>
                        <p class="card-text">$15000</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-dark text-white text-center py-3 mt-5">
    <p>&copy; 2026 MascotaFeliz - TPFINAL PWD</p>

    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>