<?php
require_once "db.php"; // Conexión a la base de datos

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
} else {
    // Mostrar el formulario de registro si el usuario accede con GET
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de Usuario</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .container {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
                text-align: center;
            }
            input {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            button {
                width: 100%;
                padding: 10px;
                background-color: #28a745;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            button:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Registro de Usuario</h2>
            <form action="registrousuario.php" method="post">
                <label for="cedula">Cédula:</label>
                <input type="number" name="cedula" id="cedula" required>

                <label for="clave1">Contraseña:</label>
                <input type="password" name="clave1" id="clave1" required minlength="4">

                <label for="clave2">Repetir Contraseña:</label>
                <input type="password" name="clave2" id="clave2" required minlength="4">

                <button type="submit">Registrarse</button>
            </form>
            <p><a href="login.php">Volver al inicio</a></p>
        </div>
    </body>
    </html>
    <?php
}
?>