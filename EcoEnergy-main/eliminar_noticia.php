<?php
require_once "ConfigsDB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idnoticia"])) {
    $idnoticia = $_POST["idnoticia"];

    $stmt = $mysqli->prepare("DELETE FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo eliminar"]);
    }

    $stmt->close();
}
?>
