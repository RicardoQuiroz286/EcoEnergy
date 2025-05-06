<?php
require_once "ConfigsDB.php";

$mysqli = getDBConnection(); // Obtener la conexión a la base de datos
error_log("POST DATA: " . print_r($_POST, true));
error_log("FILES DATA: " . print_r($_FILES, true));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"] ?? "";
    $autor = $_POST["autor"] ?? NULL;
    $fecha = $_POST["fecha"] ?? "";
    $contenido = $_POST["contenido"] ?? "";
    $categoria = $_POST["categoria"] ?? "";
    $tags = $_POST["tags"] ?? "";

    if (empty($titulo) || empty($fecha) || empty($contenido)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios"]);
        exit;
    }

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
    
        // Verificar la extensión de la imagen
        $ext = pathinfo($_FILES["imagen"]["name"], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $allowed = ["jpg", "jpeg", "png", "gif"];
    
        if (!in_array($ext, $allowed)) {
            echo json_encode(["status" => "error", "message" => "Extensión de imagen no permitida"]);
            exit;
        }
    
        // Generar nombre único para evitar sobreescritura
        $uniqueName = uniqid("img_", true) . "." . $ext;
        $rutaFinal = $uploadDir . $uniqueName;
    
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal)) {
            $imagen = $uniqueName; // Solo guardamos el nombre
        } else {
            echo json_encode(["status" => "error", "message" => "Error al subir la imagen"]);
            exit;
        }
    }
    
    // Insertar noticia en la base de datos
    $stmt = $mysqli->prepare("INSERT INTO noticias (titulo, autor, fecha, imagen, informacion, categoria, tags) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $titulo, $autor, $fecha, $imagen, $contenido, $categoria, $tags);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Noticia guardada correctamente"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Error al guardar la noticia"]);
    }
    
    $stmt->close();
    $mysqli->close();
