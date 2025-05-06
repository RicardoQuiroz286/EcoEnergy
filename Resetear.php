<?php
include('ConfigsDB.php');

if (!isset($_GET['token'])) {
    echo "Token inválido.";
    exit;
}

$token = $_GET['token'];

// Verificar si el token es válido y no ha expirado
$stmt = $mysqli->prepare("SELECT * FROM recuperaciones WHERE token = ? AND expiracion > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "El token ha expirado o no es válido.";
    exit;
}

$correo = $result->fetch_assoc()['correo'];
?>

<!-- Formulario para nueva contraseña -->
<form action="Recuperar_contraseña.php" method="POST">
    <input type="hidden" name="correo" value="<?php echo $correo; ?>">
    <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required>
    <button type="submit">Actualizar contraseña</button>
</form>
