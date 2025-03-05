<?php
// Configuración de conexión a la base de datos
$host = "192.168.2.14";  // IP del servidor MariaDB
$dbname = "aftp";        // Nombre de la base de datos
$user = "root";          // Usuario de la base de datos
$password = "cec4hkp66*"; // Contraseña de la base de datos

try {
    // Crear la conexión con PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Mostrar errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Obtener datos como array asociativo
    ]);
} catch (PDOException $e) {
    die("❌ Error de conexión a la base de datos: " . $e->getMessage());
}
?>