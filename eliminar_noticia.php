<?php
require_once "ConfigsDB.php";
$mysqli = getDBConnection(); // AQUÍ obtienes la conexión correctamente


// Verificar si se recibió el parámetro 'idnoticia' mediante POST
if (isset($_POST['idnoticia'])) {
    $idnoticia = intval($_POST['idnoticia']);

    // Preparar la consulta SQL para eliminar la noticia
    $stmt = $mysqli->prepare("DELETE FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al eliminar la noticia: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "ID de noticia no especificado."]);
}

$mysqli->close();
?>


