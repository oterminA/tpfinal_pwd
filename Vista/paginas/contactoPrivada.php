<!DOCTYPE html>
<!-- ESTE SCRIPT ES PARA LA VISTA PRIVADA, SE LO PUEDE VER CON AUTENTICACIÓN -->
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MascotaFeliz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styleEstructura.css">
</head>

<body>

<div class="container">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-info">Envianos un mensaje</h1>
    </div>

    <form id="formContacto">
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

        <button type="submit" class="btn btn-primary w-100">Enviar</button>
    </form>
    </div>
    <div id="mensaje" class="mt-3"></div> <!-- acá va a ir el mensaje de que se mandó el comentario -->
    

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/jquery_ajax.js"></script>

</body>

</html>