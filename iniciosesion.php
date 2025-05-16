<?php
session_start();
require_once "ConfigsDB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contraseña'];

    $conn = getDBConnection();

    // Verificar si es administrador
    $sql_admin = "SELECT idadministrador, correo, contraseña FROM administrador WHERE correo = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $correo);
    $stmt_admin->execute();
    $res_admin = $stmt_admin->get_result();

    if ($res_admin->num_rows == 1) {
        $admin = $res_admin->fetch_assoc();
        if (password_verify($contrasena, $admin['contraseña'])) {
            $_SESSION['admin_id'] = $admin['idadministrador'];
            $_SESSION['correo'] = $admin['correo'];
            header("Location: admin.php"); // Página de administrador
            exit();
        } else {
            $_SESSION['error_login'] = "Contraseña incorrecta.";
            header("Location: inicio_sesion.php");
            exit();
        }
    }

    // Si no es administrador, verificar si es usuario
    $sql_usuario = "SELECT idusuario, correo, contraseña FROM usuarios WHERE correo = ?";
    $stmt_user = $conn->prepare($sql_usuario);
    $stmt_user->bind_param("s", $correo);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();

    if ($res_user->num_rows == 1) {
        $usuario = $res_user->fetch_assoc();
        if (password_verify($contrasena, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['idusuario'];
            $_SESSION['correo'] = $usuario['correo'];
            header("Location: indexsi.php"); // Página de usuario
            exit();
        } else {
            $_SESSION['error_login'] = "Contraseña incorrecta.";
            header("Location: inicio_sesion.php");
            exit();
        }
    } else {
        $_SESSION['error_login'] = "Correo no registrado.";
        header("Location: inicio_sesion.php");
        exit();
    }

    // Cerrar conexiones
    $stmt_admin->close();
    $stmt_user->close();
    $conn->close();
}
?>
