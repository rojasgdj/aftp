<?php
require_once "db.php"; // Conexión a la base de datos

session_start();

// Evitar caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Si el usuario ya está autenticado, lo redirige al index
if (isset($_SESSION['logged']) && $_SESSION['logged'] === true) {
    header("Location: index.php");
    exit;
}

// Habilitar errores para depuración (eliminar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanitizar datos
    $cedula = trim($_POST['cedula'] ?? '');
    $clave1 = trim($_POST['clave1'] ?? '');
    $clave2 = trim($_POST['clave2'] ?? '');
    $hoy = date("Y-m-d H:i:s");

    // Validaciones básicas
    if (empty($cedula) || empty($clave1) || empty($clave2)) {
        echo "<script>alert('Debe completar todos los campos.'); window.history.back();</script>";
        exit;
    }

    if ($clave1 !== $clave2) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit;
    }

    try {
        // Verificar si la cédula está en `empmain`
        $stmt = $pdo->prepare("SELECT nombres, apellidos FROM empmain WHERE cedula_identidad = :cedula");
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            echo "<script>alert('El número de cédula no está registrado como empleado.'); window.history.back();</script>";
            exit;
        }

        $empleado = $stmt->fetch();
        $usrnombres = $empleado['nombres'];
        $usrapellidos = $empleado['apellidos'];

        // Verificar si la cédula ya existe en `usuarios`
        $stmt = $pdo->prepare("SELECT cedula_usuario FROM usuarios WHERE cedula_usuario = :cedula");
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('El usuario ya está registrado.'); window.history.back();</script>";
            exit;
        }

        // Hash de la contraseña
        $hashed_password = password_hash($clave1, PASSWORD_BCRYPT);

        // Insertar usuario en `usuarios`
        $stmt = $pdo->prepare("INSERT INTO usuarios (cedula_usuario, clave_usuario, fecha_creacion) 
                               VALUES (:cedula, :clave, :fecha)");
        $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
        $stmt->bindParam(':clave', $hashed_password);
        $stmt->bindParam(':fecha', $hoy);

        if ($stmt->execute()) {
            echo "<script>alert('Usuario registrado con éxito.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error al registrar el usuario.'); window.history.back();</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('Error en la conexión: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>