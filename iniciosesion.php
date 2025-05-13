<?php
session_start();
require_once "ConfigsDB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contraseña'];

    $conn = getDBConnection();

    $sql = "SELECT idusuario, correo, contraseña FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contrasena, $usuario['contraseña'])) {
            $_SESSION['usuario_id'] = $usuario['idusuario'];
            $_SESSION['correo'] = $usuario['correo'];
            header("Location: indexsi.php");
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

    $stmt->close();
    $conn->close();
}
?>
