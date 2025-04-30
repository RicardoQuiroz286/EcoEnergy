<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¿Olvidaste tu contraseña?</title>
</head>
<body>
    <h2>Recuperar contraseña</h2>
    <form action="enviar_recuperacion.php" method="POST">
        <input type="email" name="correo" placeholder="Tu correo" required>
        <button type="submit">Enviar enlace</button>
    </form>
</body>
</html>
