<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $directorio = '../data';
    $nombre_archivo = 'facturas.json';
    $ruta_archivo = $directorio . DIRECTORY_SEPARATOR . $nombre_archivo;

    if (file_exists($ruta_archivo)) {
        $facturas = json_decode(file_get_contents($ruta_archivo), true);

        if (isset($facturas[$id])) {
            unset($facturas[$id]);
            file_put_contents($ruta_archivo, json_encode(array_values($facturas), JSON_PRETTY_PRINT));
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Factura no encontrada']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Archivo no encontrado']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

