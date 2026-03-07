<?php
//esto es para poder gestionar lo q tiene que ver con la libreria php mailer que usa composer y que voy a usar para mandar los mails con los cambios del pedido de un user

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    //connfiguración del servidor
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'otermincarolina98@gmail.com';
    $mail->Password = 'tyehogpntvzblzfy';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // a quien le mando el mail y el coontenido
    $usuario = $objCompra->getObjUsuario();
    $mail->setFrom('otermincarolina98@gmail.com', 'MascotaFeliz');
    $mail->addAddress($usuario->getUsMail(), $usuario->getUsNombre());

    // lo que pongo en el correo
    $mail->isHTML(true);
    $mail->Subject = "Actualizacion de tu Pedido nro:" . $objCompra->getIdCompra();

    $cuerpo = "<h1>Hola " . $usuario->getUsNombre() . "!</h1>";
    $cuerpo .= "<p>Desde MascotaFeliz queremos informarte que tu pedido ahora está: <b>$nuevoEstadoNombre</b>.</p>";

    $mail->Body = $cuerpo;
    $mail->send();
    return true;
} catch (Exception $e) {
    return false;
}
