<?php
require_once 'db.php'; // Incluir archivo de conexión a la BD
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema Administrativo AFTP - Proveedores</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">

    <script src="SpryAssets/SpryTabbedPanels.js"></script>
    <script src="SpryAssets/SpryValidationTextField.js"></script>
    <script src="SpryAssets/SpryValidationTextarea.js"></script>

    <style>
        #apDiv1 { position: absolute; width: 1276px; height: 220px; z-index: 1; left: 4px; top: 7px; }
        #apDiv2 { position: absolute; width: 1275px; height: 396px; z-index: 2; left: 2px; top: 241px; }
        #apDiv3 { position: absolute; width: 117px; height: 28px; z-index: 3; left: 297px; top: 18px; }
    </style>
</head>
    
<body>
    <div class="titulo" id="apDiv1">
        <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
        <p>Sistema de Control de Archivo - Proveedores</p>
        <div id="apDiv3"><a href="index.php" title="Ir al Menú Inicio">Menú Inicio</a></div>
    </div>

    <div id="apDiv2">
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0">Datos Iniciales</li>
                <li class="TabbedPanelsTab" tabindex="0">Listado</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
                <!-- Formulario de Creación -->
                <div class="TabbedPanelsContent">
                    <p>Creación de Proveedores</p>
                    <form method="post" action="proveedores01valida.php">
                        <table>
                            <tr>
                                <td><label for="rif"><b>RIF</b></label></td>
                                <td><input type="text" name="rif" id="rif" required></td>
                            </tr>
                            <tr>
                                <td><label for="razonsoc"><b>Razón Social</b></label></td>
                                <td><input type="text" name="razonsoc" id="razonsoc" maxlength="100" required></td>
                            </tr>
                            <tr>
                                <td><label for="direccion"><b>Dirección</b></label></td>
                                <td><textarea name="direccion" id="direccion" cols="45" rows="5" required></textarea></td>
                            </tr>
                            <tr>
                                <td><label for="telefono"><b>Teléfono</b></label></td>
                                <td><input type="text" name="telefono" id="telefono" required></td>
                            </tr>
                            <tr>
                                <td><label for="persona"><b>Persona Contacto</b></label></td>
                                <td><input type="text" name="persona" id="persona" maxlength="50" required></td>
                            </tr>
                        </table>
                        <p><input type="submit" value="Insertar"></p>
                    </form>
                </div>

                <!-- Listado de Proveedores -->
                <div class="TabbedPanelsContent">
                    <p>Últimos Proveedores Registrados</p>
                    <?php
                    try {
                        // Consulta de proveedores
                        $stmt = $conexion->query("SELECT codprov, rif, razonsoc FROM proveedores ORDER BY fechacre DESC LIMIT 10");

                        if ($stmt->rowCount() > 0) {
                            echo "<table border='1'>";
                            echo "<tr><th>Código</th><th>RIF</th><th>Razón Social</th></tr>";

                            while ($reg = $stmt->fetch()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($reg['codprov']) . "</td>";
                                echo "<td>" . htmlspecialchars($reg['rif']) . "</td>";
                                echo "<td>" . htmlspecialchars($reg['razonsoc']) . "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                        } else {
                            echo "<p>No hay proveedores registrados.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "<p>Error en la consulta: " . $e->getMessage() . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
    </script>
</body>
</html>