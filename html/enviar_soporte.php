<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php'; // Si usas Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // ✅ Datos recibidos del formulario
    $numero = $_POST['numero'];
    $descripcion = $_POST['descripcion'];
    $sucursal = $_POST['sucursal'];
    $fechaEmision = $_POST['fecha_emision'];
    $fechaDestruccion = $_POST['fecha_destruccion'];
    $ruta = $_POST['ruta_archivo'];
    $indice = $_POST['indice'];

    // ✅ Configuración del correo
    $mail->isSMTP();
    $mail->Host       = 'smtp.tuservidor.com'; // ⚠️ Cambia por el tuyo
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tu-correo@dominio.com';
    $mail->Password   = 'tu-password';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // ✅ Correo origen y destino
    $mail->setFrom('tu-correo@dominio.com', 'Sistema AFTP');
    $mail->addAddress('destinatario@empresa.com'); // ⚠️ Cambia por el real

    // ✅ Asunto y cuerpo
    $mail->Subject = "📁 Envío de soporte [$numero] - Índice $indice";
    $mail->isHTML(true);
    $mail->Body = "
        <h3>Información del Soporte</h3>
        <p><strong>Número:</strong> $numero</p>
        <p><strong>Descripción:</strong> $descripcion</p>
        <p><strong>Sucursal:</strong> $sucursal</p>
        <p><strong>Fecha de emisión:</strong> $fechaEmision</p>
        <p><strong>Fecha de destrucción:</strong> $fechaDestruccion</p>
        <p><strong>Índice:</strong> $indice</p>
        <br>
        <p>Se adjunta el documento PDF.</p>
        <hr>
        <p style='color:gray'>Sistema AFTP</p>
    ";

    // ✅ Adjuntar el soporte
    if (file_exists($ruta)) {
        $mail->addAttachment($ruta);
    } else {
        throw new Exception("Archivo PDF no encontrado: $ruta");
    }

    // ✅ Enviar
    $mail->send();
    echo "<script>alert('📧 Correo enviado correctamente.'); window.history.back();</script>";

} catch (Exception $e) {
    echo "<script>alert('❌ Error al enviar el correo: " . $mail->ErrorInfo . "'); window.history.back();</script>";
}
