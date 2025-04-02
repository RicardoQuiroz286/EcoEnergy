<?php
require_once "ConfigsDB.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"] ?? "";
    $autor = $_POST["autor"] ?? "";
    $fecha = $_POST["fecha"] ?? "";
    $contenido = $_POST["contenido"] ?? "";

    if (empty($titulo) || empty($autor) || empty($fecha) || empty($contenido)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios"]);
        exit;
    }

    // Verificar conexión a la base de datos
    if ($mysqli->connect_error) {
        echo json_encode(["status" => "error", "message" => "Error en la conexión a la base de datos"]);
        exit;
    }

    $imagen = null;

    if (!empty($_FILES["imagen"]["name"])) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Crear la carpeta si no existe
        }

        $imagen = $uploadDir . basename($_FILES["imagen"]["name"]);
        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)) {
            echo json_encode(["status" => "error", "message" => "Error al subir la imagen"]);
            exit;
        }
    }

    // Ahora sí, incluir el campo `autor` en la consulta
    $stmt = $mysqli->prepare("INSERT INTO noticias (titulo, autor, fecha, imagen, informacion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $titulo, $autor, $fecha, $imagen, $contenido);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $mysqli->close();
}
?>
