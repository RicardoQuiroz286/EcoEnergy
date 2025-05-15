<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once "ConfigsDB.php";
$mysqli = getDBConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $titulo = $_POST["titulo"] ?? "";
    $autor = $_POST["autor"] ?? "Anónimo";
    $fecha = $_POST["fecha"] ?? date("Y-m-d H:i:s");
    $contenido = $_POST["contenido"] ?? "";

    // Validaciones básicas
    if (empty($titulo) || empty($contenido)) {
        echo json_encode([
            "status" => "error", 
            "message" => "El título y contenido son obligatorios"
        ]);
        exit;
    }

    // Manejo de imagen
    $imagen = null;
    if (!empty($_FILES["imagen"]["name"])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION));
        $allowed = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($ext, $allowed)) {
            echo json_encode([
                "status" => "error", 
                "message" => "Solo se permiten imágenes JPG, PNG o GIF"
            ]);
            exit;
        }

        $uniqueName = uniqid("img_", true) . "." . $ext;
        $rutaFinal = $uploadDir . $uniqueName;

        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal)) {
            $imagen = $uniqueName;
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Error al subir la imagen"
            ]);
            exit;
        }
    }

    // Insertar noticia
    $stmt = $mysqli->prepare("INSERT INTO noticias (titulo, autor, fecha, imagen, informacion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $titulo, $autor, $fecha, $imagen, $contenido);
    
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success", 
            "message" => "Noticia publicada correctamente",
            "data" => [
                "titulo" => $titulo,
                "autor" => $autor,
                "fecha" => $fecha
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Error al guardar: " . $mysqli->error
        ]);
    }
    
    $stmt->close();
    $mysqli->close();
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Método no permitido"
    ]);
}