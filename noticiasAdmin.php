<!DOCTYPE html>
<html lang="en">
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
            <img src="images/eco_logo.png" alt="Eco Blog Logo" class="logo">
            <nav class="navbar">
                <ul>
                    <li><a href="index.html" id="nav-home">Inicio</a></li>
                    <li><a href="#" id="nav-news">Más Noticias</a></li>
                    <li><a href="crear_noticia.html" id="nav-create">Crear Noticia</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-content container">
            <h1 id="header-title">Últimas Noticias</h1>
            <p id="header-subtitle">Mantente informado sobre las novedades en energía sostenible.</p>
        </div>
    </header>
    
    <section class="news-section">
        <div class="news-container">
            <!-- Noticias dinámicas cargadas desde la base de datos -->
            <?php
            require_once "ConfigsDB.php";  // Incluye la conexión a la base de datos
            $result = $mysqli->query("SELECT * FROM noticias ORDER BY fecha DESC");

            // Verificar si hay noticias
            if ($result->num_rows > 0) {
                // Iterar sobre cada noticia en la base de datos
                while ($row = $result->fetch_assoc()) {
                    $idnoticia = $row['idnoticia'];
                    $titulo = $row['titulo'];
                    $autor = $row['autor'];
                    $fecha = $row['fecha'];
                    $imagen = $row['imagen'];
                    $contenido = $row['informacion'];

                    // Mostrar cada noticia en una tarjeta dinámica
                    echo "
                    <div class='news-card'>
                        <img src='$imagen' alt='$titulo'>
                        <h3>$titulo</h3>
                        <p>$fecha</p>
                        <a href='#' class='btn'>Leer más</a>
                         <!-- Agregar botón para eliminar -->
                        <a href='eliminar_noticia.php?idnoticia=$idnoticia' class='btn' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta noticia?\");'>Eliminar</a>
                        <a href='editar_noticia.php?idnoticia={$row['idnoticia']}' class='btn'>Editar</a>
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
        <select id="language-selector">
            <option value="es">Español</option>
            <option value="en">English</option>
        </select>
    </footer>
    
    <script src="translateNoticias.js"></script>
</body>
</html>

