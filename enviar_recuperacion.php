<?php
include('ConfigsDB.php');

$correo = $_POST['correo'];
$token = bin2hex(random_bytes(16)); // Token aleatorio seguro
$expira = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expira en 1 hora

// Guardar token en la base de datos
$stmt = $mysqli->prepare("INSERT INTO recuperaciones (correo, token, expira) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $correo, $token, $expira);
$stmt->execute();

// Enlace de recuperación
$enlace = "http://localhost/recuperar.php?token=" . $token;

// Envío del enlace (aquí simulado con un echo, pero podrías usar PHPMailer)
echo "Hemos enviado un enlace de recuperación a tu correo.<br>";
echo "<a href='$enlace'>Haz clic aquí si no te llegó el correo</a>";
?>
