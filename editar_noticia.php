<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once "ConfigsDB.php";
$mysqli = getDBConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idnoticia = $_POST['idnoticia'] ?? '';
    $titulo = $_POST["titulo"] ?? '';
    $autor = $_POST["autor"] ?? '';
    $fecha = $_POST["fecha"] ?? '';
    $contenido = $_POST["informacion"] ?? '';

    if (empty($idnoticia) || empty($titulo) || empty($autor) || empty($fecha) || empty($contenido)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Obtener imagen actual
    $stmt = $mysqli->prepare("SELECT imagen FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $imagen = $row['imagen'] ?? '';

    // Si se subió una nueva imagen
    if (!empty($_FILES["imagen"]["name"])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imagen = $uploadDir . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen);
    }

    // Actualizar noticia
    $stmt = $mysqli->prepare("UPDATE noticias SET titulo = ?, autor = ?, fecha = ?, imagen = ?, informacion = ? WHERE idnoticia = ?");
    $stmt->bind_param("sssssi", $titulo, $autor, $fecha, $imagen, $contenido, $idnoticia);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Noticia actualizada exitosamente"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo actualizar la noticia"]);
    }
    exit;
}
