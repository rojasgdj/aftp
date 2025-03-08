<?php
require 'db.php'; // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar datos
    $nit = htmlspecialchars(trim($_POST['nit']));
    $razon_social = htmlspecialchars(trim($_POST['razon_social']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $numero_contacto = htmlspecialchars(trim($_POST['numero_contacto']));
    $status_cliente = "Activo"; // Por defecto, nuevo cliente está activo
    $fecha_creacion = date("Y-m-d H:i:s"); // Fecha y hora actual

    try {
        // Verificar si el NIT ya existe antes de insertar
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM clientes WHERE nit = :nit");
        $stmt->bindParam(':nit', $nit, PDO::PARAM_STR);
        $stmt->execute();
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            // Si el NIT ya está registrado, mostrar una alerta en el formulario
            echo "<script>
                    alert('❌ Error: El NIT ingresado ya está registrado.');
                    window.history.back(); // Regresar al formulario
                  </script>";
            exit;
        }

        // Preparar consulta para evitar inyección SQL
        $stmt = $pdo->prepare("INSERT INTO clientes (nit, razon_social, direccion, telefono, numero_contacto, status_cliente, fecha_creacion) 
                               VALUES (:nit, :razon_social, :direccion, :telefono, :numero_contacto, :status_cliente, :fecha_creacion)");

        // Ejecutar consulta
        if ($stmt->execute([
            ':nit' => $nit,
            ':razon_social' => $razon_social,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':numero_contacto' => $numero_contacto,
            ':status_cliente' => $status_cliente,
            ':fecha_creacion' => $fecha_creacion
        ])) {
            echo "<script>
                    alert('✅ Cliente registrado con éxito.');
                    window.location.href = 'clientes01.php'; // Redirigir al registro del cliente
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('⚠️ Error al registrar el cliente.');
                    window.history.back();
                  </script>";
        }

    } catch (PDOException $e) {
        echo "<script>
                alert('⚠️ Error en la conexión: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>
            alert('⚠️ Método de acceso no permitido.');
            window.history.back();
          </script>";
}
?>