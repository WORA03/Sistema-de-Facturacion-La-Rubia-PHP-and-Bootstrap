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

</head>

<body>
    
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/header.php"; ?>

    <div class="container mt-5">
        <!-- Formulario de nueva factura -->
        <form action="../actions/procesar_factura.php" method="POST" class="mb-5">
            <h2>Nueva Factura</h2>
            <div class="row mb-3">
                <div class="col">
                    <label for="fecha" class="form-label">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="col">
                    <label for="codigo_cliente" class="form-label">Código del Cliente:</label>
                    <input type="text" class="form-control" id="codigo_cliente" name="codigo_cliente" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="nombre_cliente" class="form-label">Nombre del Cliente:</label>
                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" required>
            </div>
            <div id="articulos">
                <h3>Artículos</h3>
                <div class="row mb-3">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Nombre del artículo" name="articulos[0][nombre]" required>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" placeholder="Cantidad" name="articulos[0][cantidad]" oninput="actualizarTotalArticulo(this)" required>
                    </div>
                    <div class="col">
                        <input type="number" step="0.01" class="form-control" placeholder="Precio" name="articulos[0][precio]" oninput="actualizarTotalArticulo(this)" required>
                    </div>
                    <div class="col">
                        <input type="number" step="0.01" class="form-control" placeholder="Total" name="articulos[0][total]" readonly>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary mb-3" onclick="agregarArticulo()">Agregar Artículo</button>
            <div class="mb-3">
                <label for="total" class="form-label">Total a Pagar:</label>
                <input type="number" step="0.01" class="form-control" id="total" name="total" readonly>
            </div>
            <div class="mb-3">
                <label for="comentario" class="form-label">Comentario:</label>
                <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Guardar e Imprimir Factura</button>
        </form>

    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/footer.php"; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function agregarArticulo() {
            const articulosDiv = document.getElementById('articulos');
            const numArticulos = articulosDiv.children.length;
            const nuevoArticulo = document.createElement('div');
            nuevoArticulo.className = 'row mb-3';
            nuevoArticulo.innerHTML = `
                <div class="col">
                    <input type="text" class="form-control" placeholder="Nombre del artículo" name="articulos[${numArticulos}][nombre]" required>
                </div>
                <div class="col">
                    <input type="number" class="form-control" placeholder="Cantidad" name="articulos[${numArticulos}][cantidad]" oninput="actualizarTotalArticulo(this)" required>
                </div>
                <div class="col">
                    <input type="number" step="0.01" class="form-control" placeholder="Precio" name="articulos[${numArticulos}][precio]" oninput="actualizarTotalArticulo(this)" required>
                </div>
                <div class="col">
                    <input type="number" step="0.01" class="form-control" placeholder="Total" name="articulos[${numArticulos}][total]" readonly>
                </div>
            `;
            articulosDiv.appendChild(nuevoArticulo);
        }

        function actualizarTotalArticulo(input) {
            const articuloRow = input.parentElement.parentElement;
            const cantidad = articuloRow.querySelector('input[name*="[cantidad]"]').value;
            const precio = articuloRow.querySelector('input[name*="[precio]"]').value;
            const totalInput = articuloRow.querySelector('input[name*="[total]"]');

            if (cantidad && precio) {
                const total = cantidad * precio;
                totalInput.value = total.toFixed(2);
            } else {
                totalInput.value = '';
            }

            actualizarTotalFactura();
        }

        function actualizarTotalFactura() {
            const articulosDiv = document.getElementById('articulos');
            const totalFacturaInput = document.getElementById('total');
            let totalFactura = 0;

            articulosDiv.querySelectorAll('input[name*="[total]"]').forEach(input => {
                const total = parseFloat(input.value);
                if (!isNaN(total)) {
                    totalFactura += total;
                }
            });

            totalFacturaInput.value = totalFactura.toFixed(2);
        }
    </script>
</body>

</html>