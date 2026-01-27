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

<?php
include_once '../Vista/estructura/header.php';
include_once '../util/funciones.php';

$objUser = null;

if (isset($_GET['idusuario'])) {
    $abmUsuario = new UsuarioController();
    $lista = $abmUsuario->listar("idusuario = " . $_GET['idusuario']);
    if (count($lista) > 0) {
        $objUser = $lista[0];
    }
}
?>

<body>
    <div id="wrapper" class="container mt-5">
        <h3>Actualizar datos</h3>
        <form action="../Vista/Action/actualizarLogin.php" method="POST" class="needs-validation" novalidate>
            <input type="hidden" name="idusuario" value="<?php echo $objUser->getIdUsuario(); ?>">


            <div class="col-md-6 mb-3">
                <label>Usuario</label>
                <input type="text" class="form-control" name="usnombre" value="<?php echo $objUser ? $objUser->getNombre() : '' ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Nueva Contraseña</label>
                <input type="password" class="form-control" name="uspass" placeholder="Nueva contraseña(opcional)">
            </div>
            <div class="col-md-6 mb-3">
                <label>Email</label>
                <input type="text" class="form-control" name="usmail" value="<?php echo $objUser ? $objUser->getMail() : '' ?>" required>
            </div>
            <div class="col-12">
                <button class="btn btn-success" type="submit">Guardar Cambios</button>
               <button class="btn btn-secondary"><a href="vistaAdmin.php" >Cancelar</a></button> 
            </div>
        </form>
    </div>
    <?php
include_once '../Vista/estructura/footer.php';
?>
</body>

</html>