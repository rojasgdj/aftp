<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro de Usuario</title>
</head>
<body>

<?php
require 'db.php'; // Incluir la conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y sanitizar datos
    $cedula = htmlspecialchars(trim($_POST['cedula']));
    $clave1 = htmlspecialchars(trim($_POST['clave1']));
    $clave2 = htmlspecialchars(trim($_POST['clave2']));
    $hoy = date("Y-m-d H:i:s");

    // Validar que las contraseñas coincidan
    if ($clave1 !== $clave2) {
        echo "<script>alert('Las contraseñas no son iguales.'); window.history.back();</script>";
        exit;
    }

    try {
        // Verificar si la cédula está registrada en `empmain`
        $stmt = $pdo->prepare("SELECT * FROM empmain WHERE cedula = :cedula");
        $stmt->execute([':cedula' => $cedula]);

        if ($stmt->rowCount() === 0) {
            echo "<script>alert('El número de cédula no está registrado como empleado.'); window.history.back();</script>";
            exit;
        }

        // Verificar si la cédula ya está en `usuarios`
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE cedula = :cedula");
        $stmt->execute([':cedula' => $cedula]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('El número de cédula ya está registrado.'); window.history.back();</script>";
            exit;
        }

        // Hash de la contraseña (seguro)
        $hashed_password = password_hash($clave1, PASSWORD_BCRYPT);

        // Insertar nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO usuarios (cedula, clave, fecha) VALUES (:cedula, :clave, :fecha)");
        $insert = $stmt->execute([
            ':cedula' => $cedula,
            ':clave' => $hashed_password,
            ':fecha' => $hoy
        ]);

        if ($insert) {
            echo "<script>alert('El usuario fue registrado con éxito.'); window.top.location.href = 'login.php';</script>";
        } else {
            echo "<p>Error al registrar el usuario.</p>";
        }

    } catch (PDOException $e) {
        echo "<p>Error en la conexión: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>Método de acceso no permitido.</p>";
}
?>

</body>
</html>