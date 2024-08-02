<?php
// Define el directorio y el nombre del archivo
$directorio = '../data';  // Cambia esto a la ruta correcta
$nombre_archivo = 'facturas.json';
$ruta_archivo = $directorio . DIRECTORY_SEPARATOR . $nombre_archivo;

// Obtiene el número de recibo de la URL
$numero_recibo = isset($_GET['numero']) ? $_GET['numero'] : null;

// Verifica si el archivo existe
if (file_exists($ruta_archivo)) {
    // Lee el contenido del archivo JSON
    $contenido_json = file_get_contents($ruta_archivo);
    // Decodifica el contenido JSON
    $facturas = json_decode($contenido_json, true);

    // Busca la factura correspondiente al número de recibo
    $factura_encontrada = null;
    foreach ($facturas as $factura) {
        if (isset($factura['numero_recibo']) && $factura['numero_recibo'] === $numero_recibo) {
            $factura_encontrada = $factura;
            break;
        }
    }

    // Verifica si se encontró la factura
    if ($factura_encontrada === null) {
        echo "<h1>Error: Factura no encontrada.</h1>";
        exit;
    }
} else {
    echo "<h1>Error: El archivo de facturas no se encuentra en la ruta esperada.</h1>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Factura</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="icon" href="/assets/imgs/Logo.PNG">
</head>

<body>

    <div class="recibo">
        <h2>Recibo de Factura</h2>
        <p>Número de Recibo: <strong><?php echo htmlspecialchars($factura_encontrada['numero_recibo']); ?></strong></p>
        <p>Fecha: <strong><?php echo htmlspecialchars($factura_encontrada['fecha']); ?></strong></p>
        <p>Cliente: <strong><?php echo htmlspecialchars($factura_encontrada['nombre_cliente']); ?></strong></p>
        <p>Código del Cliente: <strong><?php echo htmlspecialchars($factura_encontrada['codigo_cliente']); ?></strong></p>
        <p>Total: <strong><?php echo number_format($factura_encontrada['total'], 2); ?> $</strong></p>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Asegúrate de que 'articulos' es un array que contiene los detalles de los artículos
                if (isset($factura_encontrada['articulos']) && is_array($factura_encontrada['articulos'])) {
                    foreach ($factura_encontrada['articulos'] as $item) :
                        $subtotal = $item['cantidad'] * $item['precio']; // Calcular subtotal
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                            <td><?php echo number_format($item['precio'], 2); ?> $</td>
                            <td><?php echo number_format($subtotal, 2); ?> $</td>
                        </tr>
                <?php
                    endforeach;
                } else {
                    echo "<tr><td colspan='4'>No hay artículos en esta factura.</td></tr>";
                }
                ?>
            </tbody>
            <p>Comentario: <strong><?php echo htmlspecialchars($factura_encontrada['comentario']); ?></strong></p>
        </table>

        <a href="#" class="boton-imprimir" onclick="window.print(); return false;">Imprimir Recibo</a>
    </div>

</body>

</html>