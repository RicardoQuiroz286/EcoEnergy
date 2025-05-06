<?php
include('ConfigsDB.php');

$token = $_POST['token'];
$nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);

// Verificar si el token existe y no ha expirado
$stmt = $mysqli->prepare("SELECT correo, expira FROM recuperaciones WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $correo = $row['correo'];
    $expira = $row['expira'];

    if (strtotime($expira) > time()) {
        // Actualizar contraseña
        $stmt2 = $mysqli->prepare("UPDATE usuarios SET contraseña = ? WHERE correo = ?");
        $stmt2->bind_param("ss", $nueva_contraseña, $correo);
        $stmt2->execute();

        // Eliminar el token usado
        $mysqli->query("DELETE FROM recuperaciones WHERE token = '$token'");

        echo "Contraseña actualizada con éxito. <a href='iniciosesion.php'>Inicia sesión</a>";
    } else {
        echo "El enlace ha expirado.";
    }
} else {
    echo "Token inválido.";
}
?>
