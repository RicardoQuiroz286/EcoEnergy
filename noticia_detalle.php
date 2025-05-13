<?php
session_start(); // Asegura que la sesión esté iniciada

require_once "ConfigsDB.php";
$mysqli = getDBConnection();

// DEBUG opcional para verificar si la sesión está activa:
// echo '<pre>'; print_r($_SESSION); echo '</pre>';

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
        <!-- Logo -->
        <div class="logo">
            <a href="indexsi.php">
                <img src="images/eco_logo.png" alt="Eco Blog Logo" style="height: 60px;">
            </a>
        </div>

        <!-- Navegación -->
        <nav class="navbar">
            <ul>
                <li><a href="indexsi.php">Inicio</a></li>
                <li><a href="noticias.php">Volver a Noticias</a></li>
            </ul>
        </nav>
    </div>
</header>


    <section class="detalle-noticia container">
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>

        <img src="<?php echo !empty($noticia['imagen']) ? 'uploads/' . $noticia['imagen'] : 'images/default.jpg'; ?>" alt="Imagen de la noticia">
        <p><?php echo nl2br(htmlspecialchars($noticia['informacion'])); ?></p>
        <p><strong>Autor:</strong> <?php echo htmlspecialchars($noticia['autor']); ?></p>
        <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?></p>
    </section>

    <!-- Sección para que los usuarios inicien sesión y comenten -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <section class="comentarios container">
            <h2>Deja un comentario</h2>
            <form action="guardar_comentario.php" method="post">
                <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">
                <textarea name="comentario" rows="5" cols="60" required placeholder="Escribe tu comentario aquí..."></textarea>
                <br>
                <button type="submit">Publicar comentario</button>
            </form>
        </section>
    <?php else: ?>
        <section class="comentarios container">
            <p><a href="inicio_sesion.php">Inicia sesión</a> para dejar un comentario.</p>
        </section>
    <?php endif; ?>

    <!-- Mostrar los comentarios -->
    <section class="comentarios-list" section>

    <footer class="footer">
        <div class="footer-content container">
            <p id="footer-text">&copy; 2025 EcoEnergy - Energía Sostenible</p>
        </div>
    </footer>
