<?php
session_start(); // Asegura que la sesión esté iniciada

if (!isset($_SESSION['usuario_id'])) {
    header("Location: inicio_sesion.php");
    exit;
}

require_once "ConfigsDB.php";
$mysqli = getDBConnection();

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
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
        }

        .header, .footer {
             background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url(images/ec1.png);
            padding: 10px;
        }

        .menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 15px;
        }

        .navbar a {
            text-decoration: none;
            color: #333;
        }

        .main-content {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            box-sizing: border-box;
        }

        .noticia {
            flex: 1 1 60%;
            background-color: #ffffff;
            padding: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .comentarios-seccion {
            flex: 1 1 35%;
            background-color: #f9f9f9;
            padding: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        textarea {
            width: 100%;
            height: 100px;
            resize: vertical;
        }

        .comentario {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .footer-content {
            text-align: center;
        }
    </style>
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
                    <li><a href="indexsi.php" id="nav-home" class="translatable" data-translate-id="nav-home">Inicio</a></li>
                    <li><a href="noticias.php" id="nav-backnews" class="translatable" data-translate-id="nav-backnews">Volver a Noticias</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="main-content container">
        <section class="noticia">
            <h1 class="dynamic-deepl"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
            <p><strong class="translatable" data-translate-id="author-label">Autor:</strong> 
                <span class="dynamic-deepl"><?php echo htmlspecialchars($noticia['autor']); ?></span>
            </p>
            <p><strong class="translatable" data-translate-id="date-label">Fecha:</strong> 
                <span class="dynamic-deepl"><?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?></span>
            </p>
            <img src="<?php echo !empty($noticia['imagen']) ? 'uploads/' . htmlspecialchars($noticia['imagen']) : 'images/default.jpg'; ?>" alt="Imagen de la noticia">
            <p class="dynamic-deepl"><?php echo nl2br(htmlspecialchars($noticia['informacion'])); ?></p>
        </section>

        <aside class="comentarios-seccion">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <section>
                    <h2 class="translatable" data-translate-id="leave-comment">Deja un comentario</h2>
                    <form action="guardar_comentario.php" method="post">
                        <input type="hidden" name="idnoticia" value="<?php echo $idnoticia; ?>">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <p class="dynamic-deepl">
                                <strong>Hola <?php echo htmlspecialchars($_SESSION['usuario']); ?>,</strong> 
                                <span class="translatable" data-translate-id="comment-invite">tu opinión nos sería de mucha ayuda:</span>
                            </p>
                        <?php endif; ?>
                        <textarea name="comentario" placeholder="Tu opinion nos seria de mucha ayuda!!"></textarea>
                        <br>
                        <button type="submit" class="translatable" data-translate-id="post-comment">Publica un comentario</button>
                    </form>
                </section>
            <?php else: ?>
                <section>
                    <p>
                        <a href="inicio_sesion.php" class="translatable" data-translate-id="login-to-comment">Inicia sesión</a> 
                        <span class="translatable" data-translate-id="to-leave-comment">para dejar un comentario.</span>
                    </p>
                </section>
            <?php endif; ?>

            <section>
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
                        echo "</div>";
                    }
                } else {
                    echo "<p class='translatable' data-translate-id='no-comments'>No hay comentarios aún. ¡Sé el primero en comentar!</p>";
                }

                $stmt->close();
                ?>
            </section>
        </aside>
    </div>

    <footer class="footer">
        <div class="footer-content container">
            <p id="footer-text">&copy; 2025 EcoEnergy - Energía Sostenible</p>
        </div>
    </footer>

    <script src="diccionariolocal.js"></script>
</body>
</html>
