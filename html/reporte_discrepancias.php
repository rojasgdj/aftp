<?php
require 'db.php';

$archivo = __DIR__ . '/discrepancias_soportes.csv';
$f = fopen($archivo, 'w');
fputcsv($f, ['Número Documento', 'Tipo Seleccionado', 'Tipo Detectado', 'Fecha Emisión', 'Archivo']);

$sql = "SELECT numero_factura, tipo_documento, tipo_detectado, fecha_emision, ruta_archivo
        FROM soportes_factura
        WHERE tipo_detectado IS NOT NULL AND tipo_detectado != tipo_documento";

$stmt = $pdo->query($sql);
foreach ($stmt as $row) {
    fputcsv($f, [
        $row['numero_factura'],
        strtoupper($row['tipo_documento']),
        strtoupper($row['tipo_detectado']),
        $row['fecha_emision'],
        basename($row['ruta_archivo'])
    ]);
}

fclose($f);
echo "✅ Reporte generado: $archivo\n";
?>

