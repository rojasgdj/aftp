<?php
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

require 'vendor/autoload.php'; // PHPMailer instalado con Composer
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir y limpiar datos
    $numero = trim($_POST['numero_factura'] ?? '');
    $emailDestino = filter_var(trim($_POST['correo_destino'] ?? ''), FILTER_SANITIZE_EMAIL);
    $mensajeUsuario = trim($_POST['mensaje'] ?? '');

    if (empty($numero) || empty($emailDestino)) {
        echo "<script>alert('❌ Número de factura o correo no proporcionado.'); window.history.back();</script>";
        exit;
    }

    // Buscar información del soporte
    $stmt = $pdo->prepare("
        SELECT sf.*, pr.anios_retencion 
        FROM soportes_factura sf
        JOIN politicas_retencion pr ON sf.id_retencion = pr.id_retencion
        WHERE sf.numero_factura = ?
    ");
    $stmt->execute([$numero]);
    $soporte = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$soporte) {
        echo "<script>alert('❌ Soporte no encontrado.'); window.history.back();</script>";
        exit;
    }

    // Preparar fechas
    $fechaEmision = new DateTime($soporte['fecha_emision']);
    $fechaDestruccion = (clone $fechaEmision)->modify('+' . $soporte['anios_retencion'] . ' years');

    // Crear el correo
    $mail = new PHPMailer(true);

    try {
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rojasaftp@gmail.com';
        $mail->Password   = 'yfwstjtvwtdftmkk'; // Contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('rojasaftp@gmail.com', 'Sistema AFTP');
        $mail->addAddress($emailDestino);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = "📁 Soporte [$numero] - Índice {$soporte['indice_archivo']}";
        $mail->Body = "
            <h3>📄 Información del Soporte</h3>
            <ul>
              <li><strong>Número:</strong> {$soporte['numero_factura']}</li>
              <li><strong>Descripción:</strong> {$soporte['descripcion']}</li>
              <li><strong>Sucursal:</strong> {$soporte['sucursal']}</li>
              <li><strong>Fecha Emisión:</strong> {$fechaEmision->format('Y-m-d')}</li>
              <li><strong>Fecha Destrucción:</strong> {$fechaDestruccion->format('Y-m-d')}</li>
              <li><strong>Índice:</strong> {$soporte['indice_archivo']}</li>
            </ul>
            <hr>
            <p><strong>Mensaje del usuario:</strong></p>
            <p>" . nl2br(htmlspecialchars($mensajeUsuario)) . "</p>
            <br>
            <small style='color:gray;'>Este mensaje fue generado automáticamente desde el Sistema AFTP.</small>
        ";

        // Adjuntar el archivo PDF
        if (file_exists($soporte['ruta_archivo'])) {
            $mail->addAttachment($soporte['ruta_archivo']);
        } else {
            throw new Exception("Archivo PDF no encontrado.");
        }

        $mail->send();

        echo "<script>alert('✅ Correo enviado exitosamente.'); window.location.href='indices.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('❌ Error al enviar correo: " . addslashes($mail->ErrorInfo) . "'); window.history.back();</script>";
    }
}
?>