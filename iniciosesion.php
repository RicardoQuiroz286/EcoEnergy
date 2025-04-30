<?php
session_start();
include('ConfigsDB.php');
$mysqli = getDBConnection();

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
                $_SESSION['error_login'] = "Contraseña incorrecta.";
                header("Location: iniciosesion.php");
                exit();
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
                        header("Location: indexsi.php");
                        exit();
                    } else {
                        $_SESSION['error_login'] = "Contraseña incorrecta.";
                        header("Location: inicio_sesion.php");
                        exit();
                    }
                } else {
                    $_SESSION['error_login'] = "Correo no encontrado en ninguna de las tablas.";
                    header("Location: iniciosesion.php");
                    exit();
                }

                $stmt_usuario->close();
            } else {
                $_SESSION['error_login'] = "Error en la base de datos: " . $mysqli->error;
                header("Location: iniciosesion.php");
                exit();
            }
        }

        $stmt_admin->close();
    } else {
        $_SESSION['error_login'] = "Error en la base de datos: " . $mysqli->error;
        header("Location: iniciosesion.php");
        exit();
    }
}
?>
