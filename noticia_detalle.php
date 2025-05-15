<?php
session_start(); // Inicia la sesión

require_once "ConfigsDB.php";
$mysqli = getDBConnection();

// Verificar si se ha proporcionado un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idnoticia = $_GET['id'];

    $stmt = $mysqli->prepare("SELECT * FROM noticias WHERE idnoticia = ?");
    $stmt->bind_param("i", $idnoticia);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $noticia = $resultado->fetch_assoc();
    } else {
        echo "Noticia no encontrada.";
        exit;
    }
} else {
    echo "ID de noticia inválido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?> - Eco Blog</title>
    <link rel="stylesheet" href="noticias_detalle.css">
</head>
<body>
    <header class="header">
        <div class="menu container">
            <div class="logo">
                <a href="indexsi.php">
                    <img src="images/Ecologo.png" alt="Logo EcoBlog" style="height: 100px;">
                </a>
            </div>
            <nav class="navbar">
                <ul>
                    <li><a href="indexsi.php">Inicio</a></li>
                    <li><a href="noticias.php">Volver a Noticias</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="contenido-detalle">
    <div class="detalle-noticia">
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <img src="<?php echo !empty($noticia['imagen']) ? 'uploads/' . htmlspecialchars($noticia['imagen']) : 'images/default.jpg'; ?>" alt="Imagen de la noticia">
        <p><?php echo nl2br(htmlspecialchars($noticia['informacion'])); ?></p>
        <p><strong>Autor:</strong> <?php echo htmlspecialchars($noticia['autor']); ?></p>
        <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?></p>
    </div>

    <div class="comentarios-contenedor">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <h2>Deja un comentario</h2>
            <form action="guardar_comentario.php" method="post">
                <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">
                <p><strong>Hola <?php echo htmlspecialchars($_SESSION['correo']); ?>,</strong> tu opinión nos sería de mucha ayuda:</p>
                <textarea name="comentario" placeholder="Escribe tu comentario aquí..." required></textarea>
                <button type="submit">Publicar comentario</button>
            </form>
        <?php else: ?>
            <p><a href="inicio_sesion.php">Inicia sesión</a> para dejar un comentario.</p>
        <?php endif; ?>

        <div class="comentarios-list">
            <h2>Comentarios</h2>
            <?php
            $stmt = $mysqli->prepare("SELECT c.comentario, c.fecha, u.correo FROM comentarios c
                                      JOIN usuarios u ON c.idusuario = u.idusuario
                                      WHERE c.idnoticia = ?
                                      ORDER BY c.fecha DESC");
            $stmt->bind_param("i", $idnoticia);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<div class='comentario'>";
                    echo "<p><strong>" . htmlspecialchars($row['correo']) . "</strong> comentó el " . date("d/m/Y H:i", strtotime($row['fecha'])) . ":</p>";
                    echo "<p>" . nl2br(htmlspecialchars($row['comentario'])) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No hay comentarios aún. ¡Sé el primero en comentar!</p>";
            }

            $stmt->close();
            ?>
        </div>
    </div>
</section>


    
    <footer class="footer">
        <div class="footer-content container">
            <p id="footer-text">&copy; 2025 EcoEnergy - Energía Sostenible</p>
        </div>
    </footer>
</body>
</html>
