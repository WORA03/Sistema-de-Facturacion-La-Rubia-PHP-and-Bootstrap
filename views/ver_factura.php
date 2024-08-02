<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Factura - La Rubia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/imgs/Logo.PNG">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            margin-top: 20px;
        }


    </style>
</head>

<body>


    <div class="container mt-5">
        <?php
        // Define el directorio y el nombre del archivo
        $directorio = '../data';
        $nombre_archivo = 'facturas.json';
        $ruta_archivo = $directorio . DIRECTORY_SEPARATOR . $nombre_archivo;

        // Verifica si el ID de la factura está presente en la URL
        if (isset($_GET['id'])) {
            $id_factura = $_GET['id'];

            // Verifica si el archivo existe
            if (file_exists($ruta_archivo)) {
                // Lee el contenido del archivo JSON
                $contenido_json = file_get_contents($ruta_archivo);

                // Decodifica el contenido JSON
                $facturas = json_decode($contenido_json, true);

                // Verifica si la decodificación fue exitosa
                if ($facturas === null && json_last_error() !== JSON_ERROR_NONE) {
                    echo "<div class='alert alert-danger'>Error al decodificar el archivo JSON: " . json_last_error_msg() . "</div>";
                } else {
                    // Busca la factura por su ID
                    $factura_encontrada = null;
                    foreach ($facturas as $factura) {
                        if ($factura['id'] == $id_factura) {
                            $factura_encontrada = $factura;
                            break;
                        }
                    }

                    // Muestra los detalles de la factura si se encontró
                    if ($factura_encontrada !== null) {
                        echo "<h2>Detalles de la Factura</h2>";
                        echo "<p><strong>ID:</strong> {$factura_encontrada['id']}</p>";
                        echo "<p><strong>Fecha:</strong> {$factura_encontrada['fecha']}</p>";
                        echo "<p><strong>Código del Cliente:</strong> {$factura_encontrada['codigo_cliente']}</p>";
                        echo "<p><strong>Nombre del Cliente:</strong> {$factura_encontrada['nombre_cliente']}</p>";
                        echo "<h4>Artículos</h4>";
                        echo "<ul>";
                        foreach ($factura_encontrada['articulos'] as $articulo) {
                            echo "<li><strong>Nombre:</strong> {$articulo['nombre']}, <strong>Cantidad:</strong> {$articulo['cantidad']}, <strong>Precio:</strong> {$articulo['precio']}, <strong>Total:</strong> {$articulo['total']}</li>";
                        }
                        echo "</ul>";
                        echo "<p><strong>Total:</strong> {$factura_encontrada['total']}</p>";
                    } else {
                        echo "<div class='alert alert-warning'>Factura no encontrada.</div>";
                    }
                }
            } else {
                echo "<div class='alert alert-danger'>El archivo de facturas no se encuentra en la ruta esperada.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>No se proporcionó el ID de la factura.</div>";
        }
        ?>
        <button class="btn btn-outline-secondary btn-lg" onclick="navigateTo('/views/facturas_dia.php')">Volver a Facturas del Día</button>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //funcion para cambiar de pagina en la misma pestaña
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>
</body>

</html>