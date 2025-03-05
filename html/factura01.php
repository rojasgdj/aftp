<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Sistema Administrativo AFTP - Ingreso de Facturas</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #apDiv1 { position: absolute; width: 1276px; height: 208px; z-index: 1; left: 4px; top: 7px; }
        #apDiv2 { position: absolute; width: 1275px; height: 396px; z-index: 2; left: 6px; top: 225px; }
        #apDiv3 { position: absolute; width: 178px; height: 36px; z-index: 3; left: 297px; top: 19px; }
    </style>

    <script>
        function asignacli() {
            var codigo = document.getElementById('listacli').value;
            document.getElementById('cliente').value = codigo;
        }
    </script>

    <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet">
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet">
    <link href="SpryAssets/SpryValidationTextarea.css" rel="stylesheet">
    <script src="SpryAssets/SpryTabbedPanels.js"></script>
    <script src="SpryAssets/SpryValidationTextField.js"></script>
    <script src="SpryAssets/SpryValidationTextarea.js"></script>
</head>
    
<body>
    <div class="titulo" id="apDiv1">
        <p><img src="LogoVeramedWEB.jpg" width="293" height="119"></p>
        <p>Sistema de Control de Archivo - Ingreso de Facturas</p>
        <div id="apDiv3"><a href="index.php" title="Ir a Menú Inicio">Menú Inicio</a></div>
    </div>

    <form name="form1" method="post" action="factura01valida.php">
        <div id="apDiv2">
            <div id="TabbedPanels1" class="TabbedPanels">
                <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0">Datos</li>
                    <li class="TabbedPanelsTab" tabindex="0">Últimas Facturas Registradas</li>
                </ul>
                <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent">
                        <table width="1003" height="210" border="0">
                            <tr>
                                <td width="175">Número de Factura</td>
                                <td width="301">
                                    <span id="sprytextfield1">
                                        <input type="text" name="factura" id="factura">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Concepto</td>
                                <td>
                                    <span id="sprytextarea1">
                                        <textarea name="concepto" id="concepto" cols="45" rows="5"></textarea>
                                        <span class="textareaRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Fecha</td>
                                <td>
                                    <span id="sprytextfield2">
                                        <input type="text" name="fecha" id="fecha">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span> (dd/mm/aaaa)
                                </td>
                            </tr>
                            <tr>
                                <td>Cliente</td>
                                <td>
                                    <span id="sprytextfield3">
                                        <input type="text" name="cliente" id="cliente">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                    <select name="listacli" id="listacli" onChange="asignacli()">
                                        <?php
                                            require 'db.php'; // Conexión centralizada

                                            try {
                                                $stmt = $pdo->prepare("SELECT codcli, razonsoc FROM clientes");
                                                $stmt->execute();
                                                $clientes = $stmt->fetchAll();

                                                foreach ($clientes as $cliente) {
                                                    echo "<option value='" . htmlspecialchars($cliente['codcli']) . "'>" 
                                                        . htmlspecialchars($cliente['codcli']) . " - " . htmlspecialchars($cliente['razonsoc']) . "</option>";
                                                }
                                            } catch (PDOException $e) {
                                                die("Error en la conexión: " . htmlspecialchars($e->getMessage()));
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Monto Bs.</td>
                                <td>
                                    <span id="sprytextfield4">
                                        <input type="text" name="monto" id="monto">
                                        <span class="textfieldRequiredMsg">Se necesita un valor.</span>
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <p><input type="submit" name="Enviar" id="Enviar" value="Enviar"></p>
                    </div>
                    
                    <div class="TabbedPanelsContent">
                        <p>Últimas Facturas Registradas</p>
                        <?php
                            try {
                                $stmt = $pdo->prepare("
                                    SELECT f.numero, f.fechaems, f.monto, f.fechacre, f.codcli, c.razonsoc 
                                    FROM facturas f
                                    INNER JOIN clientes c ON f.codcli = c.codcli
                                    ORDER BY fechacre DESC 
                                    LIMIT 10
                                ");
                                $stmt->execute();
                                $facturas = $stmt->fetchAll();

                                if (count($facturas) > 0) {
                                    echo "<table border='1'>";
                                    echo "<tr><th>Código</th><th>Cliente</th><th>Número de Factura</th><th>Fecha Factura</th><th>Monto Bs.</th></tr>";

                                    foreach ($facturas as $factura) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($factura['codcli']) . "</td>";
                                        echo "<td>" . htmlspecialchars($factura['razonsoc']) . "</td>";
                                        echo "<td>" . htmlspecialchars($factura['numero']) . "</td>";
                                        echo "<td>" . htmlspecialchars($factura['fechaems']) . "</td>";
                                        echo "<td align='right'>" . htmlspecialchars($factura['monto']) . "</td>";
                                        echo "</tr>";
                                    }

                                    echo "</table>";
                                } else {
                                    echo "<p>No hay facturas registradas.</p>";
                                }

                            } catch (PDOException $e) {
                                die("Error en la conexión: " . htmlspecialchars($e->getMessage()));
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
        var sprytextarea1 = new Spry.Widget.ValidationTextarea("sprytextarea1");
        var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"dd/mm/yyyy"});
        var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "integer");
        var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "currency", {minValue:1});
    </script>
</body>
</html>