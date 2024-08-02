
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header con Logo y Botones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos para centrar el contenido del header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-bottom: 1.50px solid #c5c6c6;
        }

        .logo {
            width: 50px; 
        }

        .buttons {
            display: auto;
            gap: 15px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px; 
            margin: 0 auto;
        }

        .logodrop {
            width: 30px;
        }

        .btn {
            display: auto;
            gap: 15px;
        }

        .dropdown {
            display: auto;
        }


    </style>
</head>

<body>

    <header class="header compact">
        <div class="header-content">

            <div>
                <a href="/index.php"><img src="/assets/imgs/Logo.PNG" alt="Logo" class="logo"></a> <span class="fs-4"></span>
            </div>



            <div class="buttons">
                <?php if (isset($_SESSION['usuario'])) : ?>
                    <a href="/index.php" class="btn btn-outline-dark">Home</a>
                    <a href="/views/registrar_factura.php" class="btn btn-inline-light">Crear Cotizacion</a>
                    <a href="/views/facturas_dia.php" class="btn btn-inline-light">Ver Cotizaciones</a>
                <?php else : ?>
                    <a href="/index.php" class="btn btn-outline-dark ">Home</a>
                    <a href="/index.php" class="btn btn-inline-light no-border">About</a>
                    <a href="/index.php" class="btn btn-inline-light">Contact us</a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_SESSION['usuario'])) : ?>
                <button class="" type="button"  aria-expanded="false">
                    <a class="dropdown-item" href="#" onclick="confirmLogout()">Cerrar Sesión</a>
                </button>
            <?php else : ?>
            <div class="dropdown">
                <button class=" dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="/assets/imgs/icons8-avatar-50.png" class="logodrop">
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="/login.php">Iniciar Sesión</a></li>
                        <li><a class="dropdown-item" href="/registro.php">Registrarse</a></li>

                </ul>
            </div>
            <?php endif; ?>

        </div>
        <hr>
    </header>

    <script>
        function confirmLogout() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                window.location.href = '/logout.php'; // Redirige al script de cierre de sesión
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>