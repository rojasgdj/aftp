<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema de Biblioteca IUTV</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #apDiv1 { position: absolute; width: 1276px; height: 208px; z-index: 1; left: 4px; top: 7px; }
        #apDiv2 { position: absolute; width: 1275px; height: 396px; z-index: 2; left: 6px; top: 225px; }
        #apDiv3 { position: absolute; width: 178px; height: 36px; z-index: 3; left: 296px; top: 22px; }
    </style>
    <script>
        function asignacli() {
            var codigo = document.getElementById('listacli').value;
            document.getElementById('cliente').value = codigo;
        }
    </script>  
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
    <script src="SpryAssets/SpryMenuBar.js"></script>
    <script src="SpryAssets/SpryTabbedPanels.js"></script>
    <script src="SpryAssets/SpryValidationTextField.js"></script>
</head>
    
<body align="center">
    <div class="titulo" id="apDiv1">
        <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
        <p>Sistema de Control de Archivo - Búsqueda de Factura</p>
        <div id="apDiv3"><a href="index.php" title="Ir a Menú Inicio">Menú Inicio</a></div>
    </div>

    <form name="form1" method="post" action="resulbusqfact.php">
        <div id="apDiv2">
            <div id="TabbedPanels1" class="TabbedPanels">
                <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Coloque uno o varios datos a buscar</li>
                </ul>
                <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                        <table width="1003" height="210" border="1">
                            <tr>
                                <td width="175">Número de Factura</td>
                                <td width="301">
                                    <span id="sprytextfield1">
                                        <input type="text" name="factura" id="factura">
                                        <span class="textfieldInvalidFormatMsg">Formato no válido.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha</td>
                                <td>
                                    <span id="sprytextfield2">
                                        <input type="text" name="fecha" id="fecha2">
                                        <span class="textfieldInvalidFormatMsg">Formato no válido.</span>
                                    </span>
                                    Formato dd/mm/yyyy
                                </td>
                            </tr>
                            <tr>
                                <td>Cliente</td>
                                <td>
                                    <input type="text" name="cliente" id="cliente">
                                    <select name="listacli" id="listacli" onChange="asignacli()">
                                        <?php
                                            require 'db.php'; // Incluir la conexión

                                            try {
                                                $stmt = $pdo->query("SELECT codcli, razonsoc FROM clientes");

                                                while ($reg = $stmt->fetch()) {
                                                    $cli = htmlspecialchars($reg['codcli']);
                                                    $razon = htmlspecialchars($reg['razonsoc']);
                                                    echo "<option value=\"$cli\">$cli - $razon</option>";
                                                }
                                            } catch (PDOException $e) {
                                                echo "<option>Error al cargar clientes</option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Monto Bs.</td>
                                <td>
                                    <span id="sprytextfield3">
                                        <input type="text" name="monto" id="monto2">
                                        <span class="textfieldInvalidFormatMsg">Formato no válido.</span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <p><input type="submit" name="Enviar" id="Buscar" value="Buscar"></p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "integer", {isRequired:false});
        var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"dd/mm/yyyy", isRequired:false});
        var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "currency", {isRequired:false});
    </script>
</body>
</html>