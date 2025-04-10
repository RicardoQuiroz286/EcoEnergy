<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['correo'] !== 'admin@gmail.com') {
    header("Location: iniciosesion.php");
    exit;
}

// Bloquea volver atrás después del logout
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
?>
