<?php
include('ConfigsDB.php');

$correo = $_POST['correo'];
$nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);

// Actualizar la contraseña
$stmt = $mysqli->prepare("UPDATE usuarios SET contraseña = ? WHERE correo = ?");
$stmt->bind_param("ss", $nueva_contraseña, $correo);

if ($stmt->execute()) {
    echo "Contraseña actualizada. <a href='iniciosesion.php'>Inicia sesión</a>";
    
    // Limpieza opcional: eliminar tokens anteriores
    $mysqli->query("DELETE FROM recuperaciones WHERE correo = '$correo'");
} else {
    echo "Error al actualizar.";
}
?>
