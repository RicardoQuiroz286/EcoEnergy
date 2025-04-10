<?php
require_once "ConfigsDB.php";

// Verificar si se recibió el parámetro 'idnoticia' a través de la URL
if (isset($_GET['idnoticia'])) {
    $idnoticia = intval($_POST['idnoticia']);

    // Preparar la consulta SQL para eliminar la noticia
    $stmt = $mysqli->prepare("DELETE FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);

    // Ejecutar la consulta y verificar si la eliminación fue exitosa
    if ($stmt->execute()) {
        // Redirigir de vuelta a la página de noticias después de eliminar
        header("Location: noticias.php");  // Redirige de vuelta a la página de noticias
        exit;
    } else {
        echo "Error al eliminar la noticia: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID de noticia no especificado.";
}

$mysqli->close();
?>


