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
<?php include_once '../../estructura/header.php'; ?>

    <div id="wrapper">
        <h3>Registrarse</h3>
        <form action="../Vista/Action/altaUsuario.php" method="POST" class="needs-validation">
            <div class=" col-md-6">
                <input type="text" class="form-control" name="usnombre" placeholder="Usuario" required>
            </div>
            <div class="col-md-6">
                <input type="password" class="form-control" name="uspass" placeholder="Contraseña" required>
            </div>
            <div class=" col-md-6">
                <input type="text" class="form-control" name="usmail" placeholder="Email" required>
            </div>
            <div class="col-6">
                <input class="btn btn-success" type="submit" value="Crear cuenta"></input>
            </div>
        </form>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <?php include_once '../../estructura/foter.php'; ?>


</body>

</html>