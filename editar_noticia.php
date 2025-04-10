<?php
require_once "ConfigsDB.php";

// Verificar si se recibió el parámetro 'idnoticia' en la URL
if (isset($_GET['idnoticia'])) {
    $idnoticia = $_GET['idnoticia'];

    // Consultar la base de datos para obtener los datos de la noticia
    $stmt = $mysqli->prepare("SELECT * FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si la noticia existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $titulo = $row['titulo'];
        $autor = $row['autor'];
        $fecha = $row['fecha'];
        $imagen = $row['imagen'];
        $contenido = $row['informacion'];
    } else {
        echo "Noticia no encontrada.";
        exit;
    }
} else {
    echo "ID de noticia no especificado.";
    exit;
}

// Procesar los datos del formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idnoticia = $_POST["idnoticia"] ?? "";
    $titulo = $_POST["titulo"] ?? "";
    $autor = $_POST["autor"] ?? "";
    $fecha = $_POST["fecha"] ?? "";
    $contenido = $_POST["contenido"] ?? "";

    if (!empty($idnoticia) && !empty($titulo) && !empty($autor) && !empty($fecha) && !empty($contenido)) {
        // Ver si se subió una nueva imagen
        $imagen = $row['imagen'];  // Mantener la imagen actual si no se sube una nueva
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
            echo "<p>Noticia actualizada exitosamente.</p>";
            // Redirigir a la página de noticias después de la actualización
            header("Location: noticias.php");
        } else {
            echo "<p>No se pudo actualizar la noticia.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Todos los campos son obligatorios.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link rel="stylesheet" href="noticias.css">
</head>
<body>
    <header>
        <h1>Editar Noticia</h1>
    </header>

    <form action="editar_noticia.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">

        <label for="titulo">Título</label>
        <input type="text" name="titulo" id="titulo" value="<?php echo $titulo; ?>" required>

        <label for="autor">Autor</label>
        <input type="text" name="autor" id="autor" value="<?php echo $autor; ?>" required>

        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" id="fecha" value="<?php echo $fecha; ?>" required>

        <label for="contenido">Contenido</label>
        <textarea name="contenido" id="contenido" required><?php echo $contenido; ?></textarea>

        <label for="imagen">Imagen</label>
        <input type="file" name="imagen" id="imagen">
        <?php if ($imagen): ?>
            <p>Imagen actual: <img src="<?php echo $imagen; ?>" alt="Imagen actual" width="100"></p>
        <?php endif; ?>

        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>

