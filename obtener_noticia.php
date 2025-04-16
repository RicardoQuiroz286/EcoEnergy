<?php
require_once "ConfigsDB.php";
$mysqli = getDBConnection(); // AQUÍ obtienes la conexión correctamente

if (isset($_GET["idnoticia"])) {
    $id = $_GET["idnoticia"];
    $stmt = $mysqli->prepare("SELECT * FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($noticia = $result->fetch_assoc()) {
        echo json_encode($noticia);
    } else {
        echo json_encode(["status" => "error", "message" => "Noticia no encontrada"]);
    }

    $stmt->close();
}
?>
