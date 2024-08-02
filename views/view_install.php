
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

    <h1>Bienvenido al Instalador</h1>
    <p>Por favor, complete los siguientes campos para configurar la aplicación.</p>
    <form action="../actions/install.php" method="post">
        <div class="mb-3">
            <label for="db_host" class="form-label">Host de la Base de Datos:</label>
            <input type="text" class="form-control" name="db_host" id="db_host" required><br>

            <label for="db_name" class="form-label">Nombre de la Base de Datos:</label>
            <input type="text" class="form-control" name="db_name" id="db_name" required><br>

            <label for="db_user" class="form-label">Usuario de la Base de Datos:</label>
            <input type="text" class="form-control" name="db_user" id="db_user" required><br>
            
            <label for="db_pass" class="form-label">Contraseña de la Base de Datos
                (Dejar en blanco si no tiene):</label>
            <input type="password" class="form-control" name="db_pass" id="db_pass"><br>

            <input type="submit" class="btn btn-primary" name="install" value="Instalar">
        </div>

    </form>

    <?php include $_SERVER['DOCUMENT_ROOT'] . "/templates/footer.php"; ?>
</body>

</html>