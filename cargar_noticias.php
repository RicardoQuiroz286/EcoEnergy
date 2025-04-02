<?php
require_once "ConfigsDB.php";

$result = $mysqli->query("SELECT * FROM noticias ORDER BY fecha DESC");

$noticias = [];
while ($row = $result->fetch_assoc()) {
    $noticias[] = $row;
}

echo json_encode($noticias);
?>
