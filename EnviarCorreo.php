<?php
include('ConfigsDB.php');

$correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);

// Verifica si el correo existe
$stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $token = bin2hex(random_bytes(32));
    $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Opcional: crear tabla `recuperaciones` si no existe
    // Tabla: id, correo, token, expiracion

    $mysqli->query("INSERT INTO recuperaciones (correo, token, expiracion) VALUES ('$correo', '$token', '$expira')");

    $enlace = "http://localhost/Resetear.php?token=$token"; // Cambia URL según tu entorno
    echo "Se ha enviado un enlace a tu correo para cambiar la contraseña: <a href='$enlace'>$enlace</a>";
} else {
    echo "Correo no registrado.";
}
?>
