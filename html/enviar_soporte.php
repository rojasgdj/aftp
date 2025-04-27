<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

// Mostrar errores para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // PHPMailer por Composer
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero_factura'];
    $emailDestino = $_POST['email'];
    $mensajeUsuario = $_POST['mensaje'];

    // Buscar información del soporte
    $stmt = $pdo->prepare("SELECT sf.*, pr.anios_retencion 
                           FROM soportes_factura sf
                           JOIN politicas_retencion pr ON sf.id_retencion = pr.id_retencion
                           WHERE sf.numero_factura = ?");
    $stmt->execute([$numero]);
    $soporte = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$soporte) {
        echo "<script>alert('❌ Soporte no encontrado.'); window.history.back();</script>";
        exit;
    }

    $fechaEmision = new DateTime($soporte['fecha_emision']);
    $fechaDestruccion = (clone $fechaEmision)->modify('+' . $soporte['anios_retencion'] . ' years');

    // Crear el correo
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP de Gmail con STARTTLS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rojasaftp@gmail.com';
        $mail->Password   = ''; // contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //TLS moderno
        $mail->Port       = 587; // 📡 Puerto para TLS

        // Origen y destino
        $mail->setFrom('rojasaftp@gmail.com', 'Sistema AFTP');
        $mail->addAddress($emailDestino);

        // Asunto y cuerpo
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8'; // <-importante para acentos, ñ, emojis
        $mail->Subject = "📁 Soporte [$numero] - Índice {$soporte['indice_archivo']}";
        $mail->Body = "
            <h3>Información del Soporte</h3>
            <p><strong>Número:</strong> {$soporte['numero_factura']}</p>
            <p><strong>Descripción:</strong> {$soporte['descripcion']}</p>
            <p><strong>Sucursal:</strong> {$soporte['sucursal']}</p>
            <p><strong>Fecha Emisión:</strong> {$fechaEmision->format('Y-m-d')}</p>
            <p><strong>Fecha Destrucción:</strong> {$fechaDestruccion->format('Y-m-d')}</p>
            <p><strong>Índice:</strong> {$soporte['indice_archivo']}</p>
            <hr>
            <p>Mensaje del usuario:</p>
            <p>" . nl2br(htmlspecialchars($mensajeUsuario)) . "</p>
            <br><br>
            <p style='color:gray;'>Enviado automáticamente desde el Sistema AFTP</p>
        ";

        // Adjuntar PDF original
        if (file_exists($soporte['ruta_archivo'])) {
            $mail->addAttachment($soporte['ruta_archivo']);
        } else {
            throw new Exception("No se encontró el archivo PDF adjunto.");
        }

        // Enviar correo
        $mail->send();

        echo "<script>alert('✅ Correo enviado correctamente.'); window.location.href='indices.php';</script>";

    } catch (Exception $e) {
        echo "<script>alert('❌ Error al enviar correo: " . addslashes($mail->ErrorInfo) . "'); window.history.back();</script>";
    }
}
?>