<?php
session_start();
include('ConfigsDB.php');

// Validar que se haya enviado el formulario correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar entradas
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    // Preparar consulta para buscar al usuario en la tabla 'administrador'
    $query_admin = "SELECT * FROM administrador WHERE correo = ?";
    $stmt_admin = $mysqli->prepare($query_admin);

    if ($stmt_admin) {
        $stmt_admin->bind_param("s", $correo);
        $stmt_admin->execute();
        $resultado_admin = $stmt_admin->get_result();

        // Verificar si el correo existe en la tabla administrador
        if ($resultado_admin->num_rows === 1) {
            $usuario = $resultado_admin->fetch_assoc();

            // Verificar contraseña hasheada
            if (password_verify($contraseña, $usuario['contraseña'])) {
                // Iniciar sesión correctamente
                $_SESSION['usuario'] = $usuario['correo'];

                // Redirigir a la página del administrador
                header("Location: admin.html");
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            // Si no encuentra el correo en la tabla administrador, buscar en la tabla usuarios
            $query_usuario = "SELECT * FROM usuarios WHERE correo = ?";
            $stmt_usuario = $mysqli->prepare($query_usuario);

            if ($stmt_usuario) {
                $stmt_usuario->bind_param("s", $correo);
                $stmt_usuario->execute();
                $resultado_usuario = $stmt_usuario->get_result();

                // Verificar si el correo existe en la tabla usuarios
                if ($resultado_usuario->num_rows === 1) {
                    $usuario = $resultado_usuario->fetch_assoc();

                    // Verificar contraseña hasheada
                    if (password_verify($contraseña, $usuario['contraseña'])) {
                        // Iniciar sesión correctamente
                        $_SESSION['usuario'] = $usuario['correo'];

                        // Redirigir a la página principal (usuario normal)
                        header("Location: index.html");
                        exit();
                    } else {
                        echo "Contraseña incorrecta.";
                    }
                } else {
                    echo "Correo no encontrado en ninguna de las tablas.";
                }

                $stmt_usuario->close();
            } else {
                echo "Error en la base de datos: " . $mysqli->error;
            }
        }

        $stmt_admin->close();
    } else {
        echo "Error en la base de datos: " . $mysqli->error;
    }
}
?>
