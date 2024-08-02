<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Facturación - La Rubia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/imgs/Logo.PNG">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

</head>

<body>
    <?php include "./templates/header.php" ?>
    <div class="content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card p-4">
                        <h1 class="text-center mb-4">Bienvenido</h1>
                        <p class="text-center mb-4">Seleccione una opción para comenzar:</p>
                        <div class="d-grid gap-3">
                            <button class="btn btn-outline-dark btn-lg" onclick="navigateTo('/views/facturas_dia.php')">Ver Facturas del Día</button>
                            <button class="btn btn-outline-dark btn-lg" onclick="navigateTo('/views/registrar_factura.php')">Nueva Factura</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        //funcion para cambiar de pagina en la misma pestaña
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>

</body>

</html>