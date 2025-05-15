<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="page-title">Eco Blog</title>
    <link rel="stylesheet" href="menu.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <header class="header">
    <div class="menu container">
        <!-- Logo -->
        <div class="logo">
            <img src="images/Ecologo.png" alt="Logo EcoBlog" style="height: 100px;">
        </div>
        
        <!-- Menú de navegación -->
        <nav class="navbar">
            <ul>
                <li><a href="noticias.php" id="nav-news">Más noticias</a></li>
                <li>
                    <?php if (isset($_SESSION['correo'])): ?>
                        <h5><span class="user-welcome"><?php echo htmlspecialchars($_SESSION['correo']); ?></span></h5>
                        <a href="cerrar_sesion.php" class="logout-link">Cerrar sesión</a>
                    <?php else: ?>
                        <h5><span class="login-status">No has iniciado sesión</span></h5>
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
    
    <!-- Contenido del header -->
    <div class="header-content container">
        <h1>ECO BLOG</h1>
        <p>
            Bienvenidos a EcoBlog, un espacio dedicado a promover la energía sostenible 
            y el acceso a fuentes limpias de energía para todos. Aquí encontrarás información 
            sobre energías renovables, eficiencia energética y consejos para reducir tu huella 
            energética en el día a día.
        </p>
    </div>
</header>

    <!-- Sección de información sobre ODS 7 -->
    <section class="coffee">
        <div class="coffe-content container">
            <h2 id="section-title-1">¿Qué es el ODS 7?</h2>
            <p class="txt-p" id="section-description-1">
                La ODS 7 forma parte de los Objetivos de Desarrollo Sostenible de la ONU y busca garantizar el acceso universal a una energía asequible, fiable, sostenible y moderna. Algunos de sus principales objetivos incluyen:
            </p>
        </div>
    </section>

    <!-- Sección de metas del ODS 7 -->
    <main class="services">
        <div class="services-content container">
            <h2 id="section-title-2">METAS DEL ODS 7</h2>

            <div class="blog-content">
                <div class="blog-2">
                    <img src="images/blog1.jpg" alt=""> 
                    <i class='bx bx-leaf'></i>
                    <h3 id="goal-1">Garantizar el acceso universal a servicios energéticos asequibles.</h3> 
                </div>

                <div class="blog-2">
                    <img src="images/blog2.jpg" alt=""> 
                    <i class='bx bx-buildings'></i>
                    <h3 id="goal-2">Aumentar la proporción de energías renovables en la matriz energética global.</h3>    
                </div>

                <div class="blog-2">
                    <img src="images/blog3.jpg" alt="">
                    <i class='bx bxs-bar-chart-alt-2'></i>
                    <h3 id="goal-3">Duplicar la tasa de mejora de la eficiencia energética. </h3> 
                </div>
            </div>
        </div>
    </main>

    <!-- Sección de lo más reciente -->
<section class="coffee">
    <div class="coffe-content container">
        <h2 id="recent-news-title">LO MÁS RECIENTE</h2>
    </div>
</section>

<section class="news-section">
    <div class="news-container container-fluid ">
        <?php
        require_once "ConfigsDB.php";
        $mysqli = getDBConnection();
        $result = $mysqli->query("SELECT * FROM noticias ORDER BY fecha DESC LIMIT 3");

        if ($result->num_rows > 0) {
            $counter = 0; // Contador para alternar diseños
            while ($row = $result->fetch_assoc()) {
                $idnoticia = $row['idnoticia'];
                $titulo = htmlspecialchars($row['titulo']);
                $autor = htmlspecialchars($row['autor']);
                $fecha = date("d/m/Y", strtotime($row['fecha']));
                $imagen = !empty($row['imagen']) ? "uploads/" . htmlspecialchars($row['imagen']) : "images/default.jpg";
                $contenido = substr(htmlspecialchars($row['informacion']), 0, 400) . "...";

                // Alternamos el diseño basado en el contador
                if ($counter % 2 == 0) {
                    // Diseño con imagen a la izquierda
                    echo "<div class=\"row align-items-center py-4\">
                            <div class=\"col-md-6 imagen-noticia text-center\">
                                <img src=\"$imagen\" alt=\"Imagen de $titulo\" class=\"img-fluid noticia-img\">
                            </div>
                            <div class=\"col-md-6 general-1\">
                                <div class=\"contenido-flex\">
                                    <div class=\"texto-noticia\">
                                        <h1 id=\"news-title-$idnoticia\">$titulo</h1>
                                        <p id=\"news-description-$idnoticia\">
                                            <strong>Autor:</strong> $autor<br>
                                            <strong>Fecha:</strong> $fecha<br>
                                            $contenido
                                        </p>
                                        <div class=\"text-center\">
                                            <a href=\"noticia_detalle.php?id=$idnoticia\" class=\"btn-1\" id=\"news-link-$idnoticia\">Más información...</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>";
                } else {
                    // Diseño con imagen a la derecha
                    echo "<div class=\"row align-items-center py-4\">
                            <div class=\"col-md-6 general-1\">
                                <div class=\"contenido-flex\">
                                    <div class=\"texto-noticia\">
                                        <h1 id=\"news-title-$idnoticia\">$titulo</h1>
                                        <p id=\"news-description-$idnoticia\">
                                            <strong>Autor:</strong> $autor<br>
                                            <strong>Fecha:</strong> $fecha<br>
                                            $contenido
                                        </p>
                                        <div class=\"text-center\">
                                            <a href=\"noticia_detalle.php?id=$idnoticia\" class=\"btn-1\" id=\"news-link-$idnoticia\">Más información...</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=\"col-md-6 imagen-noticia text-center\">
                                <img src=\"$imagen\" alt=\"Imagen de $titulo\" class=\"img-fluid noticia-img\">
                            </div>
                          </div>";
                }
                $counter++; // Incrementamos el contador
            }
        } else {
            echo "<p class=\"text-center\">No hay noticias recientes disponibles.</p>";
        }
        ?>
    </div>
</section>

    <!-- Sección de más noticias -->
    <section class="blog container">
    <h2 id="more-news-title">MAS NOTICIAS</h2>
    <p id="more-news-description">¡¡Ponte al día con todas las noticias!!</p>

    <div class="blog-content">
        <?php
        $result = $mysqli->query("SELECT * FROM noticias ORDER BY fecha DESC LIMIT 3 OFFSET 3");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $idnoticia = $row['idnoticia'];
                $titulo = htmlspecialchars($row['titulo']);
                $imagen = !empty($row['imagen']) ? "uploads/" . htmlspecialchars($row['imagen']) : "images/default.jpg";

                echo "<div class=\"blog-1\">
                        <a href=\"noticia_detalle.php?id=$idnoticia\"><img src=\"$imagen\" alt=\"Imagen de $titulo\"></a>
                        <a href=\"noticia_detalle.php?id=$idnoticia\"><h3 id=\"news-$idnoticia\">$titulo</h3></a>
                      </div>";
            }
        } else {
            echo "<p class=\"text-center\">No hay más noticias disponibles.</p>";
        }
        ?>
    </div>

    <a href="noticias.php" class="btn-1" id="more-news-link">Más noticias</a>
</section>


    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content container">
            <p id="footer-text">&copy; 2025 EcoEnergy - Energía Sostenible</p>
        </div>
    </footer>

    <script src="translateIndex.js"></script>
</body>

</html>