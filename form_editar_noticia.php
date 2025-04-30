<?php
require_once "ConfigsDB.php";
$mysqli = getDBConnection();

$idnoticia = $_GET['idnoticia'] ?? '';
$titulo = $autor = $fecha = $contenido = $imagen = '';

if (!empty($idnoticia)) {
    $stmt = $mysqli->prepare("SELECT * FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $titulo = $row['titulo'];
        $autor = $row['autor'];
        $fecha = $row['fecha'];
        $contenido = $row['informacion'];
        $imagen = $row['imagen'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Noticia</title>
</head>
<body>
    <h1>Editar Noticia</h1>
    <form action="editar_noticia.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">
        
        <label for="titulo">Título</label>
        <input type="text" name="titulo" id="titulo" value="<?php echo $titulo; ?>" required><br>

        <label for="autor">Autor</label>
        <input type="text" name="autor" id="autor" value="<?php echo $autor; ?>" required><br>

        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" id="fecha" value="<?php echo $fecha; ?>" required><br>

        <label for="informacion">Contenido</label>
        <textarea name="informacion" id="informacion" required><?php echo $contenido; ?></textarea><br>

        <label for="imagen">Imagen</label>
        <input type="file" name="imagen" id="imagen"><br>
        <?php if ($imagen): ?>
            <p>Imagen actual: <img src="<?php echo $imagen; ?>" width="100"></p>
        <?php endif; ?>

        <label for="categoria">Categoría</label>
        <input type="text" name="categoria" id="categoria" value="energia"><br>

        <label for="destacada">Destacada</label>
        <select name="destacada">
            <option value="0">No</option>
            <option value="1">Sí</option>
        </select><br>

        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>
