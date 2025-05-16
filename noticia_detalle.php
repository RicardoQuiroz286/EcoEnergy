<?php
session_start(); // Asegura que la sesión esté iniciada

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
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
                    <li><a href="indexsi.php" id="nav-home" class="translatable" data-translate-id="nav-home">Inicio</a></li>
                    <li><a href="noticias.php" id="nav-backnews" class="translatable" data-translate-id="nav-backnews">Volver a Noticias</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="detalle-noticia container">
        <h1 class="dynamic-deepl"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
         <!-- Autor y fecha, pueden ir con traducción fija de etiquetas -->
         <p><strong class="translatable" data-translate-id="author-label">Autor:</strong> 
           <span class="dynamic-deepl"><?php echo htmlspecialchars($noticia['autor']); ?></span></p>

        <p><strong class="translatable" data-translate-id="date-label">Fecha:</strong> 
           <span class="dynamic-deepl"><?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?></span></p>
        <img src="<?php echo !empty($noticia['imagen']) ? 'uploads/' . $noticia['imagen'] : 'images/default.jpg'; ?>" alt="Imagen de la noticia">
        <!-- Información de la noticia dinámica traducible -->
        <p class="dynamic-deepl"><?php echo nl2br(htmlspecialchars($noticia['informacion'])); ?></p>
    </section>

    <!-- Sección para que los usuarios inicien sesión y comenten -->
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <section class="comentarios container">
        <h2 class="translatable" data-translate-id="leave-comment">Deja un comentario</h2>
            <form action="guardar_comentario.php" method="post">
                <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <p class="dynamic-deepl"><strong>Hola <?php echo $_SESSION['usuario']; ?>,</strong> deja tu comentario:</p>
                <?php endif; ?>
                <textarea name ="comentario" placeholder="Escribe tu comentario aquí..."></textarea>
                <br>
                <button type="submit" class="translatable" data-translate-id="post-comment">Publica un comentario</button>
            </form>
        </section>
    <?php else: ?>
        <section class="comentarios container">
        <p><a href="inicio_sesion.php" class="translatable" data-translate-id="Inic">Inicia sesión</a> para dejar un comentario.</p>
        </section>
    <?php endif; ?>

    <!-- Mostrar los comentarios -->
    <section class="comentarios-list container">
        <h2 class="translatable" data-translate-id="comments">Comentarios</h2>
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
                echo "<p><strong class='dynamic-deepl'>" . htmlspecialchars($row['correo']) . "</strong> <span class='translatable' data-translate-id='date-label'>comentó el</span> " . date("d/m/Y H:i", strtotime($row['fecha'])) . ":</p>";
                echo "<p>" . nl2br(htmlspecialchars($row['comentario'])) . "</p>";
                echo "<hr>";
                echo "</div>";
            }
        } else {
            echo "<p class='translatable' data-translate-id='no-comments'>No hay comentarios aún. ¡Sé el primero en comentar!</p>";
        }



        $stmt->close();
        ?>
    </section>
     <!-- Aquí cargas tu script del diccionario y traducción -->
     <script src="diccionariolocal.js"></script>
</body>
</html>

