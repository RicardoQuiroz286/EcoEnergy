<?php
require_once "ConfigsDB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idnoticia = $_POST["idnoticia"] ?? "";
    $titulo = $_POST["titulo"] ?? "";
    $autor = $_POST["autor"] ?? "";
    $fecha = $_POST["fecha"] ?? "";
    $contenido = $_POST["contenido"] ?? "";

    if (!empty($idnoticia) && !empty($titulo) && !empty($autor) && !empty($fecha) && !empty($contenido)) {
        // Ver si se subió una nueva imagen
        $imagen = null;
        if (!empty($_FILES["imagen"]["name"])) {
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imagen = $uploadDir . basename($_FILES["imagen"]["name"]);
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen);
        }

        // Actualizar con o sin imagen
        if ($imagen) {
            $stmt = $mysqli->prepare("UPDATE noticias SET titulo = ?, autor = ?, fecha = ?, imagen = ?, informacion = ? WHERE idnoticia = ?");
            $stmt->bind_param("sssssi", $titulo, $autor, $fecha, $imagen, $contenido, $idnoticia);
        } else {
            $stmt = $mysqli->prepare("UPDATE noticias SET titulo = ?, autor = ?, fecha = ?, informacion = ? WHERE idnoticia = ?");
            $stmt->bind_param("ssssi", $titulo, $autor, $fecha, $contenido, $idnoticia);
        }

        if ($stmt->execute()) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "No se pudo actualizar"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios"]);
    }
}
?>
