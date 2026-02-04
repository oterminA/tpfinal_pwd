<!DOCTYPE html>
<!-- este es el registrarse de la pagina -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Css/TP5/login.css">
    <title>Login</title>
</head>

<body>
    <?php include_once '../Vista/estructura/header.php'; ?>

    <div id="wrapper">
        <h3>Envianos un mensaje</h3>
        <form action="#">
            <div class="form-floating">
                <label>Correo electrónico</label>
                <input type="email" class="form-control" id="email">
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="nombre">
                <label>Nombre y apellido</label>
            </div>
            <div class="form-floating">
                <label>Comentario</label>
                <textarea class="form-control" id="msg"></textarea>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <?php include_once '../Vista/estructura/footer.php'; ?>

</body>

</html>