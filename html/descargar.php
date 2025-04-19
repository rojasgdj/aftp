<?php
session_start();
session_regenerate_id(true);

// ───────────── Seguridad de sesión ─────────────
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    http_response_code(403);
    exit("⚠ Acceso no autorizado. Debe iniciar sesión.");
}

// ───────────── Validar parámetro de archivo ─────────────
if (!isset($_GET['file']) || empty($_GET['file'])) {
    http_response_code(400);
    exit("⚠ Parámetro 'file' no especificado.");
}

$filename = basename($_GET['file']); // Sanitiza el nombre (evita rutas)
$filepath = "/data/soportes/$filename"; // Ruta absoluta del archivo

// ───────────── Validar existencia ─────────────
if (!file_exists($filepath)) {
    http_response_code(404);
    exit("⚠ El archivo solicitado no existe.");
}

// ───────────── Cabeceras para descarga ─────────────
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filepath));

// ───────────── Leer archivo ─────────────
readfile($filepath);
exit;