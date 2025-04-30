<?php
include('ConfigsDB.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $nueva = $_POST['nueva_contraseña'];
    $confirmar = $_POST['confirmar_contraseña'];

    if ($nueva !== $confirmar) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    $hash = password_hash($nueva, PASSWORD_DEFAULT);

    $query = "UPDATE usuarios SET contraseña = ?, token = NULL, expira_token = NULL WHERE token = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $hash, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Contraseña cambiada. <a href='iniciosesion.php'>Inicia sesión</a>";
    } else {
        echo "Error al cambiar la contraseña.";
    }

    $stmt->close();
}
?>
