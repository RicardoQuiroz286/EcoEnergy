<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('ConfigsDB.php');
    $mysqli = getDBConnection();

    // Sanitizar entradas
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'];
    $confirmar = $_POST['confirmar_contraseña'];

    // Validación básica
    if (!$correo || !$contraseña || !$confirmar) {
        echo "<script>alert('Todos los campos son obligatorios.'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('El correo no es válido.'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }

    if ($contraseña !== $confirmar) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }

    if (strlen($contraseña) < 8) {
        echo "<script>alert('La contraseña debe tener al menos 8 caracteres.'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }

    // Verificar si ya existe el correo
    $verificar = "SELECT idusuario FROM usuarios WHERE correo = ?";
    $stmt = $mysqli->prepare($verificar);
    if (!$stmt) {
        echo "<script>alert('Error al preparar la consulta: " . $mysqli->error . "'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('Este correo ya está registrado.'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }

    // Hashear la contraseña
    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $insertar = "INSERT INTO usuarios (correo, contraseña) VALUES (?, ?)";
    $insert_stmt = $mysqli->prepare($insertar);
    if (!$insert_stmt) {
        echo "<script>alert('Error al preparar la inserción: " . $mysqli->error . "'); window.location.href='inicio_sesion.php';</script>";
        exit;
    }
    $insert_stmt->bind_param("ss", $correo, $hash);

    if ($insert_stmt->execute()) {
        echo "<script>alert('Usuario registrado exitosamente.'); window.location.href='inicio_sesion.php';</script>";
    } else {
        echo "<script>alert('Error al registrar: " . $mysqli->error . "'); window.location.href='inicio_sesion.php';</script>";
    }

    // Cerrar conexiones
    $stmt->close();
    $insert_stmt->close();
    $mysqli->close();
}
?>
