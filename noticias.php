<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Noticias - Eco Blog</title>
    <link rel="stylesheet" href="noticias.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <header class="header">
        <div class="menu container">
            <!-- Logo -->
            <div class="logo">
                <a href="indexsi.php">
                    <img src="images/eco_logo.png" alt="Logo EcoBlog" style="height: 60px;">
                </a>
            </div>

            <!-- Menú de navegación -->
            <nav class="navbar">
                <ul>
                    <li><a href="indexsi.php" id="nav-news">Inicio</a></li>
                    <li>
                        <?php if (isset($_SESSION['correo'])): ?>
                            <h5><span class="user-welcome"><?php echo htmlspecialchars($_SESSION['correo']); ?></span></h5>
                            <a href="cerrar_sesion.php" class="logout-link">Cerrar sesión</a>
                        <?php else: ?>
                            <span class="login-status">No has iniciado sesión</span>
                            <a href="inicio_sesion.php" class="login-link">Iniciar sesión</a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <select id="language-selector" class="language-selector" onchange="changeLanguage(this.value)">
                            <option value="es">Español</option>
                            <option value="en">English</option>
                        </select>
                    </li>
                </ul>
            </nav>
        </div>
    </header>


    
    <section class="news-section">
        <div class="news-container">
            <?php
            require_once "ConfigsDB.php";  // Incluye la conexión a la base de datos

            $mysqli = getDBConnection(); // Obtiene la conexión a la base de datos

            $result = $mysqli->query("SELECT * FROM noticias ORDER BY fecha DESC");

            // Verificar si hay noticias
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $idnoticia = $row['idnoticia'];
                    $titulo = $row['titulo'];
                    $autor = $row['autor'];
                    $fecha = date("d/m/Y", strtotime($row['fecha'])); // Formato de fecha
                    $imagen = !empty($row['imagen']) ? "uploads/" . $row['imagen'] : "images/default.jpg"; // Imagen con fallback
                    $contenido = substr($row['informacion'], 0, 150) . "..."; // Resumen del contenido

                    echo "
                    <div class='news-card'>
                        <img src='$imagen' alt='$titulo'>
                        <h3>$titulo</h3>
                        <p><strong>Autor:</strong> $autor</p>
                        <p><strong>Fecha:</strong> $fecha</p>
                        <p>$contenido</p>
                        <a href='noticia_detalle.php?id=$idnoticia' class='btn'>Leer más</a>
                    </div>";
                }
            } else {
                echo "<p>No hay noticias disponibles.</p>";
            }
            ?>
        </div>
    </section>
    
    <footer class="footer">
        <div class="footer-content container">
            <p id="footer-text">&copy; 2025 EcoEnergy - Energía Sostenible</p>
        </div>
    </footer>
    
    <script src="translateNoticias.js"></script>
</body>
</html>

