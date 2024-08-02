<?php
if (isset($_POST['install'])) {
    $db_host = $_POST['db_host'];
    $db_name = $_POST['db_name'];
    $db_user = $_POST['db_user'];
    $db_pass = $_POST['db_pass']; // Asegúrate de recibir la contraseña si es necesaria

    // Crea la conexión a la base de datos
    $conn = new mysqli($db_host, $db_user, $db_pass);

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Verifica si la base de datos ya existe
    $sql = "SHOW DATABASES LIKE '$db_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "La base de datos ya existe. No se puede crear de nuevo.<br>";
        echo "<a href='/index.php'>Ir a la aplicación</a>";
    } else {
        // Crea la base de datos
        $sql = "CREATE DATABASE $db_name";
        if ($conn->query($sql) === TRUE) {
            echo "Base de datos creada correctamente.<br>";
        } else {
            echo "Error al crear la base de datos: " . $conn->error;
        }

        // Selecciona la base de datos
        $conn->select_db($db_name);

        // Crea la tabla clientes
        $sql = "CREATE TABLE clientes (
            codigo_cliente INT PRIMARY KEY,
            nombre_cliente VARCHAR(255) NOT NULL
        )";

        if ($conn->query($sql) === TRUE) {
            echo "Tabla 'clientes' creada correctamente.<br>";
        } else {
            echo "Error al crear la tabla 'clientes': " . $conn->error;
        }

        // Crea la tabla ventas
        $sql = "CREATE TABLE ventas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fecha DATE NOT NULL,
            codigo_cliente INT,
            total DECIMAL(10, 2) NOT NULL,
            comentario TEXT,
            numero_recibo VARCHAR(255) NOT NULL,
            FOREIGN KEY (codigo_cliente) REFERENCES clientes(codigo_cliente)
        )";

        if ($conn->query($sql) === TRUE) {
            echo "Tabla 'ventas' creada correctamente.<br>";
        } else {
            echo "Error al crear la tabla 'ventas': " . $conn->error;
        }

        // Crea la tabla articulos
        $sql = "CREATE TABLE articulos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            venta_id INT,
            nombre VARCHAR(255) NOT NULL,
            cantidad INT NOT NULL,
            precio DECIMAL(10, 2) NOT NULL,
            total DECIMAL(10, 2) NOT NULL,
            FOREIGN KEY (venta_id) REFERENCES ventas(id)
        )";

        if ($conn->query($sql) === TRUE) {
            echo "Tabla 'articulos' creada correctamente.<br>";
        } else {
            echo "Error al crear la tabla 'articulos': " . $conn->error;
        }

        // Guarda la configuración en un archivo
        $config_content = "<?php\n";
        $config_content .= "define('DB_HOST', '$db_host');\n";
        $config_content .= "define('DB_NAME', '$db_name');\n";
        $config_content .= "define('DB_USER', '$db_user');\n";
        $config_content .= "define('DB_PASS', '$db_pass');\n";

        file_put_contents('config.php', $config_content);

        echo "Instalación completada. <a href='/index.php'>Ir a la aplicación</a>";
    }

    $conn->close();
}

