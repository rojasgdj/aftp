<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE); // Mostrar errores sin warnings innecesarios

require 'db.php'; // Archivo de conexión a la base de datos

try {
    // Validar datos del formulario
    $cedula = trim($_POST['cedula'] ?? '');
    $clave1 = trim($_POST['clave1'] ?? '');

    if (empty($cedula) || empty($clave1)) {
        echo "<script>alert('Debe ingresar usuario y contraseña.'); window.history.back();</script>";
        exit;
    }

    // Verificar si la cédula existe en `empmain`
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

    // Verificar si la cédula ya tiene un usuario registrado
    $stmt = $pdo->prepare("SELECT cedula_usuario, clave_usuario FROM usuarios WHERE cedula_usuario = :cedula");
    $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch();

        // Verificar la contraseña con `password_verify()`
        if (password_verify($clave1, $usuario['clave_usuario'])) {
            session_regenerate_id(true); // Seguridad adicional

            $_SESSION['usrcedula'] = $usuario['cedula_usuario'];
            $_SESSION['usrnombres'] = $usrnombres;
            $_SESSION['usrapellidos'] = $usrapellidos;
            $_SESSION['logged'] = true;

            echo "<script>alert('Sesión iniciada: $usrnombres $usrapellidos'); window.location.href = 'index.php';</script>";
            exit;
        }
    }

    echo "<script>alert('Usuario y/o contraseña incorrecta.'); window.location.href = 'login.php';</script>";

} catch (PDOException $e) {
    die("<script>alert('Error en la conexión: " . addslashes($e->getMessage()) . "');</script>");
}
?>