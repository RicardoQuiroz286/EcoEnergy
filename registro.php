<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('ConfigsDB.php');

    // Sanitizar entradas
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contraseña = $_POST['contraseña'];
    $confirmar = $_POST['confirmar_contraseña'];
    $genero = $_POST['genero'];

    // Validación básica
    if (!$correo || !$contraseña || !$confirmar || !$genero) {
        echo "Todos los campos son obligatorios.";
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "El correo no es válido.";
        exit;
    }

    if ($contraseña !== $confirmar) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    if (strlen($contraseña) < 6) {
        echo "La contraseña debe tener al menos 6 caracteres.";
        exit;
    }

    // Verificar si ya existe el correo
    $verificar = "SELECT idusuario FROM usuarios WHERE correo = ?";
    $stmt = $mysqli->prepare($verificar);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "Este correo ya está registrado.";
        exit;
    }

    // Hashear la contraseña
    $hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $insertar = "INSERT INTO usuarios (correo, contraseña, genero) VALUES (?, ?, ?)";
    $insert_stmt = $mysqli->prepare($insertar);
    $insert_stmt->bind_param("sss", $correo, $hash, $genero);

    if ($insert_stmt->execute()) {
        echo "Registro exitoso. Redirigiendo al inicio de sesión...";
        header("refresh:2;url=nicio_sesion.html");
    } else {
        echo "Error al registrar: " . $mysqli->error;
    }

    // Cerrar conexiones
    $stmt->close();
    $insert_stmt->close();
    $mysqli->close();
}
?>
