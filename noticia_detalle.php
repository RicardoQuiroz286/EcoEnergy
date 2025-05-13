<?php
session_start(); // Asegura que la sesión esté iniciada

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio_sesion.php");  // Redirige al inicio de sesión
    exit;  // Detiene la ejecución después de la redirección
}

// Debug para verificar si la sesión está activa y el usuario está logueado
//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';

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
            <img src="images/eco_logo.png" alt="Eco Blog Logo" class="logo">
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
        <p><strong>Autor:</strong> <?php echo htmlspecialchars($noticia['autor']); ?></p>
        <p><strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?></p>
        <img src="<?php echo !empty($noticia['imagen']) ? 'uploads/' . $noticia['imagen'] : 'images/default.jpg'; ?>" alt="Imagen de la noticia">
        <p><?php echo nl2br(htmlspecialchars($noticia['informacion'])); ?></p>
    </section>

    <!-- Sección para que los usuarios inicien sesión y comenten -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <section class="comentarios container">
            <h2>Deja un comentario</h2>
            <form action="guardar_comentario.php" method="post">
                <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <p><strong>Hola <?php echo $_SESSION['usuario']; ?>,</strong> deja tu comentario:</p>
                <?php endif; ?>
                <textarea placeholder="Escribe tu comentario aquí..."></textarea>
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
    <section class="comentarios-list container">
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
                echo "<hr>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay comentarios aún. ¡Sé el primero en comentar!</p>";
        }

        $stmt->close();
        ?>
    </section>

