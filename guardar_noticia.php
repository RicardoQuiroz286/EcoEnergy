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

        // Codificamos el nombre del archivo para evitar problemas con espacios y caracteres especiales
        $imagen = $uploadDir . rawurlencode(basename($_FILES["imagen"]["name"]));

        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen)) {
            error_log("Error al subir la imagen a la ruta: $imagen"); // Esto ayuda a depurar
            echo json_encode(["status" => "error", "message" => "Error al subir la imagen"]);
            exit;
        } else {
            error_log("Imagen subida correctamente a: $imagen"); // Confirmación silenciosa
        }
    }

    // Insertar noticia en la base de datos
    $stmt = $mysqli->prepare("INSERT INTO noticias (titulo, autor, fecha, imagen, informacion, categoria, tags) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $titulo, $autor, $fecha, $imagen, $contenido, $categoria, $tags);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Noticia guardada correctamente"]);
        exit;
    }
    
    
    

    $stmt->close();
    $mysqli->close();
}
?>

