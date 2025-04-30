<?php
$token = $_GET['token'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer contraseña</title>
</head>
<body>
    <h2>Escribe tu nueva contraseña</h2>
    <form action="actualizar_contraseña.php" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="password" name="nueva_contraseña" placeholder="Nueva contraseña" required>
        <button type="submit">Actualizar contraseña</button>
    </form>
</body>
</html>
