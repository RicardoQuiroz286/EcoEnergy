<?php
session_start();
require_once "ConfigsDB.php";

if (!isset($_SESSION['usuario_id']) || !isset($_POST['idnoticia']) || empty($_POST['comentario'])) {
    header("Location: noticia_detalle.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$idnoticia = intval($_POST['idnoticia']);
$comentario = trim($_POST['comentario']);

$mysqli = getDBConnection();

$stmt = $mysqli->prepare("INSERT INTO comentarios (idusuario, idnoticia, comentario, fecha) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("iis", $usuario_id, $idnoticia, $comentario);

if ($stmt->execute()) {
    header("Location: noticias_detalle.php?id=" . $idnoticia);
} else {
    echo "Error al guardar el comentario.";
}
?>
