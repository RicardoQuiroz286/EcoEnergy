<?php
// Conectar a la base de datos (ajusta estos parámetros según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ecoblog"; // nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el número de noticias publicadas
$sql_noticias = "SELECT COUNT(*) AS total_noticias FROM noticias";
$result_noticias = $conn->query($sql_noticias);
$noticias = $result_noticias->fetch_assoc();
$total_noticias = $noticias['total_noticias'];

// Obtener el número de usuarios
$sql_usuarios = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
$result_usuarios = $conn->query($sql_usuarios);
$usuarios = $result_usuarios->fetch_assoc();
$total_usuarios = $usuarios['total_usuarios'];

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - EcoBlog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><i class="fas fa-leaf"></i> ECOENERGY</h4>
        <a href="#" id="btn-dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="#" id="btn-crear"><i class="fas fa-plus-circle"></i> Crear Noticia</a>
        <a href="#" id="btn-noticias"><i class="fas fa-newspaper"></i> Gestionar Noticias</a>
        <!-- Agregar botón de cerrar sesión al final -->
        <div class="mt-auto"> <!-- Esto lo coloca al final -->
            <a href="#" id="btn-logout" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <!-- Sección Dashboard (visible por defecto) -->
        <div id="dashboard-section">
            <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Noticias Publicadas</h5>
                            <p class="card-text display-4"><?php echo $total_noticias; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios</h5>
                            <p class="card-text display-4"><?php echo $total_usuarios; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección Crear Noticia -->
        <div id="crear-section" style="display: none;">
            <h2><i class="fas fa-plus-circle"></i> Crear Nueva Noticia</h2>
            
            <form id="formulario">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" required>
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor">
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen Destacada</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="contenido" class="form-label">Contenido</label>
                            <textarea class="form-control" id="contenido" name="contenido" rows="8" required></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="mostrarPreview()">
                            <i class="fas fa-eye"></i> Vista Previa
                        </button>
                        <button type="button" class="btn btn-success" onclick="agregarNoticia()">
                            <i class="fas fa-save"></i> Publicar Noticia
                        </button>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Opciones Adicionales</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="categoria" class="form-label">Categoría</label>
                                    <select class="form-select" id="categoria" name="categoria">
                                        <option value="energia">Energía</option>
                                        <option value="sostenibilidad">Sostenibilidad</option>
                                        <option value="tecnologia">Tecnología</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="tags" class="form-label">Etiquetas (separadas por comas)</label>
                                    <input type="text" class="form-control" id="tags" name="tags">
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="destacada" name="destacada">
                                    <label class="form-check-label" for="destacada">Noticia Destacada</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Vista previa -->
            <div id="noticia-preview" class="noticia-preview" style="display: none;">
                <h3 id="preview-titulo">Título de la Noticia</h3>
                <div class="meta">
                    <span id="preview-autor">Autor</span> | 
                    <span id="preview-fecha">Fecha</span> | 
                    <span id="preview-categoria">Categoría</span>
                </div>
                <img id="preview-imagen" src="" alt="Imagen de la noticia" style="display: none; max-width: 100%; height: auto;">
                <div id="preview-contenido" class="contenido">
                    Contenido de la noticia aparecerá aquí...
                </div>
            </div>
        </div>

        <!-- Sección Gestionar Noticias -->
        <div id="noticias-section" style="display: none;">
            <h1><i class="fas fa-newspaper"></i> Gestión de Noticias</h1>       

            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaNoticias">
                    <!-- Las noticias se cargarán aquí dinámicamente -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalEditarLabel">Editar Noticia</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editId">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label>Título</label>
                                <input type="text" id="editTitulo" class="form-control mb-2" required>
                            </div>
                            <div class="mb-3">
                                <label>Autor</label>
                                <input type="text" id="editAutor" class="form-control mb-2" required>
                            </div>
                            <div class="mb-3">
                                <label>Fecha</label>
                                <input type="date" id="editFecha" class="form-control mb-2" required>
                            </div>
                            <div class="mb-3">
                                <label>Contenido</label>
                                <textarea id="editContenido" class="form-control mb-2" rows="6" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Imagen Actual</label>
                                <img id="editImagenActual" src="" class="img-thumbnail mb-2 d-block" style="max-width: 100%; height: auto;">
                            </div>
                            <div class="mb-3">
                                <label>Nueva Imagen (opcional)</label>
                                <input type="file" id="editImagen" class="form-control mb-2" accept="image/*">
                                <img id="preview-imagen-edit" src="" class="img-thumbnail mb-2 d-none" style="max-width: 100%; height: auto;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicion()">
                        <i class="fas fa-save"></i> Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vista Previa -->
    <div class="modal fade" id="modalVistaPrevia" tabindex="-1" aria-labelledby="modalVistaPreviaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVistaPreviaLabel">Vista Previa de la Noticia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <h3 id="vistaTitulo"></h3>
                    <img id="vistaImagen" src="" alt="Imagen de la noticia" class="img-fluid mb-3" />
                    <p id="vistaContenido"></p>
                    <p><strong>Publicado el:</strong> <span id="vistaFecha"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para cerrar sesión -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirmar cierre de sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro que deseas cerrar sesión?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmLogout">Cerrar sesión</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para publicar noticia -->
    <div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="publishModalLabel">Confirmar publicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro que deseas publicar esta noticia?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="confirmPublish">Publicar</button>
                </div>
            </div>
        </div>
    </div>                                                                                                                                                                

    <!-- Modal de éxito -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage">Noticia publicada correctamente</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar noticia -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro que deseas eliminar esta noticia? Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para guardar cambios -->
    <div class="modal fade" id="saveChangesModal" tabindex="-1" aria-labelledby="saveChangesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveChangesModalLabel">Confirmar cambios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro que deseas guardar los cambios realizados a esta noticia?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmSaveChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>