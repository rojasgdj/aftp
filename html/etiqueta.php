<?php
session_start();
if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: login.php");
    exit;
}

$indice = $_GET['indice'] ?? '';
$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

function formatFecha($fecha) {
    return date("d/m/Y", strtotime($fecha));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Etiqueta Carpeta <?= htmlspecialchars($indice) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            text-align: center;
            padding: 20px;
        }

        .etiqueta {
            width: 5cm;
            height: 21cm;
            border: 2px solid black;
            padding: 10px;
            margin: auto;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            page-break-inside: avoid;
        }

        .etiqueta h1 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .etiqueta h2 {
            font-size: 16px;
            margin: 8px 0;
        }

        .etiqueta p {
            font-size: 14px;
            margin: 4px 0;
        }

        .logo {
            max-width: 60px;
            margin-bottom: 10px;
        }

        .print-btn {
            margin-top: 30px;
        }

        @media print {
            @page {
                size: portrait;
                margin: 0;
            }

            body {
                background: white;
                padding: 0;
            }

            .print-btn {
                display: none;
            }

            .etiqueta {
                border: 2px solid black !important;
            }
        }
    </style>
<script>
window.onload = () => {
  window.print();
  setTimeout(() => {
    window.close();
  }, 100);
};
</script>
</head>
<body>

    <div class="etiqueta">
        <img src="img/aftp-logo.png" alt="Logo AFTP" class="logo">
        <h1>Índice <?= htmlspecialchars($indice) ?></h1>
        <h2>Soportes Archivados</h2>
        <p><strong>Desde:</strong><br><?= formatFecha($desde) ?></p>
        <p><strong>Hasta:</strong><br><?= formatFecha($hasta) ?></p>
        <p><strong>Sistema:</strong> AFTP</p>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">🖨️ Reimprimir Etiqueta</button>
        <br><br>
        <a href="indices.php">⬅ Volver a indices</a>
    </div>

</body>
</html>