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
                <a href="index.php">
                    <img src="images/Ecologo.png" alt="Logo EcoBlog" style="height: 80px;">
                </a>
            </div>

            <!-- Menú de navegación -->
            <nav class="navbar">
                <ul>
                    <li><a href="index.php" id="nav-home" class="translatable" data-translate-id="nav-home">Inicio</a></li>
                    <li><a href="noticias.php" id="nav-news" class="translatable" data-translate-id="nav-news">Más Noticias</a></li>
                    <li>
                        <?php if (isset($_SESSION['correo'])): ?>
                            <h5><span class="user-welcome"><?php echo htmlspecialchars($_SESSION['correo']); ?></span></h5>
                            <a href="cerrar_sesion.php" class="logout-link translatable" data-translate-id="logout">Cerrar sesión</a>
                        <?php else: ?>
                            <span class="login-status translatable" data-translate-id="not-logged-in">No has iniciado sesión</span>
                            <a href="inicio_sesion.php" class="login-link translatable" data-translate-id="login">Iniciar sesión</a>
                        <?php endif; ?>
                    </li>
                    <div id="language-selector" class="language-selector">
                    <button class="language-option" data-lang="es">
                        <img src="https://flagcdn.com/w40/es.png" alt="Español">
                    </button>
                    <button class="language-option" data-lang="en">
                        <img src="https://flagcdn.com/w40/gb.png" alt="English">
                    </button>
                </div>
                </ul>
                
            </nav>
        </div>
        <div class="header-content container">
            <h1 id="header-title" class="translatable" data-translate-id="header-title">Últimas Noticias</h1>
            <p id="header-subtitle" class="translatable" data-translate-id="header-subtitle">Manténgase informado de las últimas noticias sobre energía sostenible</p>
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
                        <h3 class='dynamic-deepl'>$titulo</h3>
                        <p><strong class='translatable' data-translate-id='author-label'>Autor:</strong> <span class='dynamic-deepl'>$autor</span></p>
                        <p><strong class='translatable' data-translate-id='date-label'>Fecha:</strong> $fecha</p>
                        <p class='dynamic-deepl'>$contenido</p>
                        <a href='noticia_detalle.php?id=$idnoticia' class='btn dynamic-deepl'>Leer más</a>
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
    
<script src="diccionariolocal.js"></script>
</body>
</html>

