<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema Administrativo AFTP - Clientes</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #apDiv1 { position: absolute; width: 1276px; height: 207px; z-index: 1; left: 4px; top: 7px; }
        #apDiv2 { position: absolute; width: 1275px; height: 396px; z-index: 2; left: 4px; top: 219px; }
        #apDiv3 { position: absolute; width: 117px; height: 28px; z-index: 3; left: 294px; top: 20px; }
    </style>

    <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet" type="text/css">

    <script src="SpryAssets/SpryTabbedPanels.js"></script>
    <script src="SpryAssets/SpryValidationTextField.js"></script>
    <script src="SpryAssets/SpryValidationTextarea.js"></script>
</head>
    
<body>
    <div class="titulo" id="apDiv1">
        <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
        <p>Sistema de Control de Archivos - Clientes</p>
        <div id="apDiv3"><a href="index.php" title="Ir al Menú Inicio">Menú Inicio</a></div>
    </div>

    <div id="apDiv2">
        <div id="TabbedPanels1" class="TabbedPanels">
            <ul class="TabbedPanelsTabGroup">
                <li class="TabbedPanelsTab" tabindex="0">Datos Iniciales</li>
                <li class="TabbedPanelsTab" tabindex="0">Listado</li>
            </ul>
            <div class="TabbedPanelsContentGroup">
                <div class="TabbedPanelsContent">
                    <p>Creación de Clientes</p>
                    <form name="form1" method="post" action="cliente01valida.php">
                        <table width="849" border="0">
                            <tr>
                                <td width="236">RIF</td>
                                <td width="603">
                                    <span id="sprytextfield1">
                                        <input type="text" name="rif" id="rif">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Razón Social</td>
                                <td>
                                    <span id="sprytextfield2">
                                        <input name="razonsoc" type="text" id="razonsoc" size="100" maxlength="100">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Dirección</td>
                                <td>
                                    <span id="sprytextarea1">
                                        <textarea name="direccion" id="direccion" cols="45" rows="5"></textarea>
                                        <span class="textareaRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Teléfono</td>
                                <td>
                                    <span id="sprytextfield3">
                                        <input type="text" name="telefono" id="telefono">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Persona Contacto</td>
                                <td>
                                    <span id="sprytextfield4">
                                        <input name="persona" type="text" id="persona" size="50" maxlength="50">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <p><input type="submit" id="insertar" value="Insertar"></p>
                    </form>
                </div>

                <div class="TabbedPanelsContent">
                    <p>Últimos Clientes</p>

                    <?php
                    require 'db.php'; // Incluir la conexión a la base de datos

                    try {
                        // Consulta de clientes
                        $stmt = $pdo->prepare("SELECT codcli, rif, razonsoc FROM clientes ORDER BY fechacre DESC");
                        $stmt->execute();
                        $clientes = $stmt->fetchAll();

                        if (count($clientes) > 0) {
                            echo "<table border='1'>";
                            echo "<tr><th>Código</th><th>RIF</th><th>Razón Social</th></tr>";

                            foreach ($clientes as $cliente) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($cliente['codcli']) . "</td>";
                                echo "<td>" . htmlspecialchars($cliente['rif']) . "</td>";
                                echo "<td>" . htmlspecialchars($cliente['razonsoc']) . "</td>";
                                echo "</tr>";
                            }

                            echo "</table>";
                        } else {
                            echo "<p>No hay clientes registrados.</p>";
                        }

                    } catch (PDOException $e) {
                        echo "<p>Error al cargar clientes: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div> 
    </div>

    <script>
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
        var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
        var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none");
        var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
    </script>
</body>
</html>