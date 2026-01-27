<?php
    include_once '../util/funciones.php';
    $sesion = new Session();

    if (!$sesion->validar()) {
        header('Location: ../login.php');
        exit;
    }
    $abmUsuario = new UsuarioController();
    $arrayUsers = $abmUsuario->listar();
    include_once '../Vista/estructura/header.php';

    ?>
    
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../Css/TP5/login.css">
    <title>Listado de Usuarios</title>
</head>

<body>

    <div class="container mt-5" id="wrapper">
        <h2>Lista de Usuarios</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Usuario</th>
                    <th>Email</th>
                    <!-- <th>Estado</th> -->
                    <!-- <th>Acciones</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                if (is_array($arrayUsers)) {
                    foreach ($arrayUsers as $objUser) {
                        $estado = ($objUser->getDeshabilitado() == null || $objUser->getDeshabilitado() == "0000-00-00 00:00:00") ? "Habilitado" : "Deshabilitado";

                        echo '<tr>';
                        // echo '<td>' . $objUser->getIdUsuario() . '</td>';
                        echo '<td>' . $objUser->getNombre() . '</td>';
                        echo '<td>' . $objUser->getMail() . '</td>';
                        // echo '<td>' . $estado . '</td>';
                        echo '<td>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No hay usuarios registrados.</td></tr>';
                }
                ?>
            </tbody>
        </table>
        <div class="col-12">
            <button class="btn btn-success"> <a href="../Vista/registrarse.php">Registrar a un usuario</a></button>
            <button class="btn btn-warning"> <a href="../Vista/Action/cerrarSesion.php">Cerrar sesión</a></button>
        </div>
    </div>
    <?php     include_once '../Vista/estructura/footer.php';
 ?>
</body>

</html>
