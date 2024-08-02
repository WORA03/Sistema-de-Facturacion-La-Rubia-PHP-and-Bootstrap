<?php

// Define el directorio y el nombre del archivo
$directorio = '../data';  // Cambié esto a la ruta correcta
$nombre_archivo = 'facturas.json';
$ruta_archivo = $directorio . DIRECTORY_SEPARATOR . $nombre_archivo;

// Inicializa la suma total de las facturas
$suma_total = 0;

// Verifica si el archivo existe
if (file_exists($ruta_archivo)) {
    // Lee el contenido del archivo JSON
    $contenido_json = file_get_contents($ruta_archivo);

    // Decodifica el contenido JSON
    $facturas = json_decode($contenido_json, true);

    // Verifica si la decodificación fue exitosa
    if ($facturas === null && json_last_error() !== JSON_ERROR_NONE) {
        echo "<tr><td colspan='5'>Error al decodificar el archivo JSON: " . json_last_error_msg() . "</td></tr>";
    } else {
        // Muestra las facturas
        if (empty($facturas)) {
            echo "<tr><td colspan='5'>No hay facturas para mostrar.</td></tr>";
        } else {
            foreach ($facturas as $factura) {
                echo "<tr>";
                echo "<td>{$factura['id']}</td>";
                echo "<td>{$factura['fecha']}</td>";
                echo "<td>{$factura['codigo_cliente']}</td>";
                echo "<td>{$factura['nombre_cliente']}</td>";
                echo "<td>{$factura['total']}</td>";
                echo "<td>";
                echo "<button class='btn btn-primary' onclick='verFactura({$factura['id']})'>Ver Detalles</button> ";
                echo "<button class='btn btn-danger' onclick='eliminarFactura({$factura['id']})'>Eliminar</button>";
                echo "</td>";
                echo "</tr>";

                // Añade el total de la factura a la suma total
                $suma_total += $factura['total'];
            }
        }
        // Muestra la suma total de las facturas
        echo "<tr>";
        echo "<td colspan='4' class='text-end'><strong>Total de todas las facturas:</strong></td>";
        echo "<td><strong>$" . number_format($suma_total, 2) . "</strong></td>";
        echo "<td></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>El archivo de facturas no se encuentra en la ruta esperada.</td></tr>";
}
?>
    <!-- Modal para ver detalles de la factura -->
    <div class="modal fade" id="verFacturaModal" tabindex="-1" aria-labelledby="verFacturaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verFacturaModalLabel">Detalles de la Factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detallesFactura">
                    <!-- Aquí se cargarán los detalles de la factura -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
<script>
    function eliminarFactura(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta factura?')) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('../actions/eliminar_factura.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Factura eliminada con éxito');
                        location.reload();
                    } else {
                        alert('Error al eliminar la factura');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }

    function verFactura(id) {
            window.location.href = '../views/ver_factura.php?id=' + id;
        }
</script>
</script>