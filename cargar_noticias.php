<?php
require_once "ConfigsDB.php"; // Asegúrate de que el nombre esté bien
$mysqli = getDBConnection(); // AQUÍ obtienes la conexión correctamente


$result = $mysqli->query("SELECT * FROM noticias ORDER BY fecha DESC");

$noticias = [];
while ($row = $result->fetch_assoc()) {
    $noticias[] = $row;
}

echo json_encode($noticias);
?>
