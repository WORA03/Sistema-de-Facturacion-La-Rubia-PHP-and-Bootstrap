<?php
session_start();
include './config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $clave = $_POST['clave'];

    // Conectar a la base de datos
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Buscar usuario
    $sql = "SELECT clave_hash FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $nombre_usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($clave_hash);
        $stmt->fetch();

        // Verificar contraseña
        if (password_verify($clave, $clave_hash)) {
            $_SESSION['usuario'] = $nombre_usuario;
            header('Location: index.php');
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="d-flex h-100 text-center text-white bg-light">
    <?php include "./templates/header.php" ?>
    <div class="container align-items-center justify-content-center min-vh-200 my-4 ">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                <div class="card border border-light-subtle rounded-3 shadow-sm">
                    <div class="card-body p-3 p-md-4 p-xl-5">
                        <div class="col-12">
                            <img src="/assets/imgs/Logo.PNG" alt="Logo" class="logo">
                        </div>
                        <hr>
                        <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Sign in to your account</h2>
                        <form action="login.php" method="post">
                            <input type="hidden" name="action" value="login">
                            <div class="form-group">
                                <label for="nombre_usuario">Username:</label>
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="clave">Password:</label>
                                <input type="password" class="form-control" id="clave" name="clave" required>
                            </div>
                            <div class="col-12">
                                <div class="d-grid my-3">
                                    <button type="submit" class="btn btn-primary btn-lg">Login</button>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-between">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" name="rememberMe" id="rememberMe">
                                        <label class="form-check-label text-secondary" for="rememberMe">
                                            Keep me logged in
                                        </label>
                                    </div>
                                    <a href="#!" class="link-primary text-decoration-none">Forgot password?</a>
                                </div>
                            </div>
                            <div class="col-12">
                                <p class="m-0 text-secondary text-center">Don't have an account? <a href="/cotizaciones_app/views/auth/register.php" class="link-primary text-decoration-none">Sign up</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "./templates/footer.php" ?>
</body>


</html>