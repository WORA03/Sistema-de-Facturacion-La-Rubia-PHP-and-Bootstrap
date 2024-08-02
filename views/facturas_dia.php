<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas del Día</title>
    <!-- Incluye Bootstrap CSS si lo necesitas -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/imgs/Logo.PNG">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/header.php"; ?>

    <!-- Tabla de facturas -->
    <div class="container mt-5">
        <h2>Facturas del Día</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número de Factura</th>
                    <th>Fecha</th>
                    <th>Codigo del Cliente</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php include $_SERVER['DOCUMENT_ROOT'] . "/actions/mostrar_facturas.php"; ?>
            </tbody>
        </table>
    </div>
    <!-- Incluye Bootstrap JS si lo necesitas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/footer.php"; ?>

</body>

</html>