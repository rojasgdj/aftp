<?php
session_start();
session_regenerate_id(true); // Evita secuestro de sesión

require 'db.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula'] ?? '');
    $clave1 = trim($_POST['clave1'] ?? '');

    if (empty($cedula) || empty($clave1)) {
        echo "<script>alert('Debe ingresar usuario y contraseña.'); window.history.back();</script>";
        exit;
    }

    // Verificar si el usuario existe en la base de datos
    $stmt = $pdo->prepare("SELECT cedula_usuario, clave_usuario FROM usuarios WHERE cedula_usuario = :cedula");
    $stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch();

        if (password_verify($clave1, $usuario['clave_usuario'])) {
            session_regenerate_id(true); // Seguridad adicional

            $_SESSION['usrcedula'] = $usuario['cedula_usuario'];
            $_SESSION['logged'] = true;

            echo "<script>window.location.href = 'index.php';</script>";
            exit;
        }
    }

    echo "<script>alert('Usuario y/o contraseña incorrecta.'); window.history.back();</script>";
} else {
    header("Location: login.php");
    exit;
}
?>