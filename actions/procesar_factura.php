<?php
//Proceso de guardado en base de datos ----

include './config/config.php';

// Recibir datos del formulario
$fecha = $_POST['fecha'];
$codigo_cliente = $_POST['codigo_cliente'];
$nombre_cliente = $_POST['nombre_cliente'];
$total = $_POST['total'];
$comentario = $_POST['comentario'];
$articulos = $_POST['articulos'];

// Inicializar variables de respuesta
$message = '';
$success = false;
$factura_id = null;

// Verificar si el cliente ya existe
$stmt = $conn->prepare("SELECT codigo_cliente FROM clientes WHERE codigo_cliente = ?");
$stmt->bind_param("i", $codigo_cliente);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $message = 'El código de cliente ya existe.';
    echo json_encode(['success' => $success, 'message' => $message]);
    echo '<a href="/views/registrar_factura.php">Regresar a Registro de Facturas</a>';
    exit;
} else {
    // Inserta el cliente si no existe
    $stmt = $conn->prepare("INSERT INTO clientes (codigo_cliente, nombre_cliente) VALUES (?, ?)");
    $stmt->bind_param("is", $codigo_cliente, $nombre_cliente);
    $stmt->execute();
}

// Inserta la venta
$stmt = $conn->prepare("INSERT INTO ventas (fecha, codigo_cliente, total, comentario, numero_recibo) VALUES (?, ?, ?, ?, ?)");
$numero_recibo = uniqid('FAC-');
$stmt->bind_param("isids", $fecha, $codigo_cliente, $total, $comentario, $numero_recibo);
$stmt->execute();
$venta_id = $stmt->insert_id;
$stmt->close();

// Inserta los artículos
foreach ($articulos as $articulo) {
    $nombre = $articulo['nombre'];
    $cantidad = $articulo['cantidad'];
    $precio = $articulo['precio'];
    $total_articulo = $articulo['total'];

    $stmt = $conn->prepare("INSERT INTO articulos (venta_id, nombre, cantidad, precio, total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isidd", $venta_id, $nombre, $cantidad, $precio, $total_articulo);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

$success = true;
$message = 'Factura registrada correctamente.';
echo json_encode(['success' => $success, 'message' => $message]);
echo '<a href="/views/registrar_factura.php">Regresar a Registro de Facturas</a>';

//Proceso de guardado en formato JSON ----

// Recibir datos del formulario
$fecha = $_POST['fecha'];
$codigo_cliente = $_POST['codigo_cliente'];
$nombre_cliente = $_POST['nombre_cliente'];
$total = $_POST['total'];
$comentario = $_POST['comentario'];
$articulos = $_POST['articulos'];

// Define el directorio y el nombre del archivo
$directorio = '../data';
$nombre_archivo = 'facturas.json';
$ruta_archivo = $directorio . DIRECTORY_SEPARATOR . $nombre_archivo;

// Verifica si el directorio existe, si no, lo crea
if (!is_dir($directorio)) {
    mkdir($directorio, 0777, true);
}

// Leer el archivo JSON existente
$json_file = $ruta_archivo;
$facturas = [];

// Verifica si el JSON está vacío 
if (file_exists($json_file)) {
    $facturas = json_decode(file_get_contents($json_file), true);
    if ($facturas === null) {
        $facturas = [];
    }
}

// Inicializar variables de respuesta
$message = '';
$success = false;
$factura_id = null;

// Verificar si el código de cliente ya existe
foreach ($facturas as $factura) {
    if ($factura['codigo_cliente'] === $codigo_cliente) {
        $message = 'El código de cliente ya existe.';
        $success = false;
        echo json_encode(['success' => $success, 'message' => $message]);
        echo '<a href="/views/registrar_factura.php">Regresar a Registro de Facturas</a>';
        exit;
    }
}

// Generar un nuevo ID para la factura
$nuevo_id = count($facturas) + 1;

// Crear la nueva factura
$nueva_factura = [
    'id' => $nuevo_id,
    'fecha' => $fecha,
    'codigo_cliente' => $codigo_cliente,
    'nombre_cliente' => $nombre_cliente,
    'total' => $total,
    'comentario' => $comentario,
    'articulos' => $articulos,
    'numero_recibo' => uniqid('FAC-') // Generar un número único para el recibo
];

// Agregar la nueva factura al array
$facturas[$nuevo_id] = $nueva_factura;

// Guardar el array actualizado en el archivo JSON
if (file_put_contents($json_file, json_encode($facturas, JSON_PRETTY_PRINT))) {
    $message = 'Factura guardada con éxito';
    $success = true;
    $factura_id = $nuevo_id;
} else {
    $message = 'Error al guardar la factura';
    $success = false;
    $factura_id = null;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/imgs/Logo.PNG">

    <title>Procesar Facturas</title>

</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">¡Factura Guardada!</h4>
                    <div class="col-md-12">
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <!-- Pantalla de cargar cuando el guardado de la factura sea exitoso -->
                        <script>
                            document.addEventListener('DOMContentLoaded', (event) => {
                                const success = <?php echo json_encode($success); ?>;
                                const message = <?php echo json_encode($message); ?>;
                                const factura_id = <?php echo json_encode($factura_id); ?>;
                                const numero_recibo = <?php echo json_encode($nueva_factura['numero_recibo']); ?>; // Agregar el número de recibo

                                if (success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Éxito',
                                        text: message + ' (ID: ' + factura_id + ')',
                                        showConfirmButton: true, // Mostrar el botón de confirmación
                                        didClose: () => {
                                            // Mostrar el botón de impresión después de que se cierre la alerta
                                            const printButton = document.createElement('button');
                                            printButton.innerText = 'Imprimir Recibo';
                                            printButton.className = 'btn btn-primary mt-3';
                                            printButton.style.display = 'block';
                                            printButton.style.margin = '0 auto';
                                            printButton.onclick = () => {
                                                window.open('../actions/imprimir_recibo.php?numero=' + numero_recibo, '_blank');
                                            };

                                            // Agregar el botón al cuerpo del documento
                                            document.body.appendChild(printButton);
                                        }
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: message,
                                        showConfirmButton: true,
                                    }).then(() => {
                                        window.location.href = '/index.php';
                                    });
                                }
                                // Boton para volver al index
                                if (success) {
                                    // Mostrar el botón de volver al index después de que se cierre la alerta
                                    const homeButton = document.createElement('button');
                                    homeButton.innerText = 'Volver al Home';
                                    homeButton.className = 'btn btn-primary mt-3';
                                    homeButton.style.display = 'block';
                                    homeButton.style.margin = '0 auto';
                                    homeButton.onclick = () => {
                                        navigateTo('../index.php');
                                    };

                                    // Agregar el botón al cuerpo del documento
                                    document.body.appendChild(homeButton);
                                }
                            });
                            //funcion para cambiar de pagina en la misma pestaña
                            function navigateTo(page) {
                                window.location.href = page;
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>