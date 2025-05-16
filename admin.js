// Cargar Bootstrap desde CDN
const scriptBootstrap = document.createElement('script');
scriptBootstrap.src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js";
document.head.appendChild(scriptBootstrap);

 // Mostrar secciones según el botón clickeado
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.id.replace('btn-', '');
                mostrarSeccion(target);
            });
        });

        function mostrarSeccion(seccion) {
            // Ocultar todas las secciones
            document.querySelectorAll('.content > div').forEach(div => {
                div.style.display = 'none';
            });
            
            // Mostrar la sección seleccionada
            document.getElementById(`${seccion}-section`).style.display = 'block';
            
            // Si es la sección de noticias, actualizar la tabla
            if (seccion === 'noticias') {
                actualizarTabla();
            }
        }

        // Mostrar vista previa de la noticia
        function mostrarPreview() {
            const titulo = document.getElementById('titulo').value;
            const autor = document.getElementById('autor').value;
            const fecha = document.getElementById('fecha').value;
            const contenido = document.getElementById('contenido').value;
            const categoria = document.getElementById('categoria').value;
            const imagen = document.getElementById('imagen').files[0];
            
            document.getElementById('preview-titulo').textContent = titulo || "Título de la Noticia";
            document.getElementById('preview-autor').textContent = autor || "Autor";
            document.getElementById('preview-fecha').textContent = formatearFecha(fecha) || "Fecha";
            document.getElementById('preview-categoria').textContent = categoria || "Categoría";
            document.getElementById('preview-contenido').textContent = contenido || "Contenido de la noticia aparecerá aquí...";
            
            if (imagen) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-imagen').src = e.target.result;
                    document.getElementById('preview-imagen').style.display = 'block';
                }
                reader.readAsDataURL(imagen);
            } else {
                document.getElementById('preview-imagen').style.display = 'none';
            }
            
            document.getElementById('noticia-preview').style.display = 'block';
        }

        function formatearFecha(fecha) {
            if (!fecha) return '';
            const opciones = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(fecha).toLocaleDateString('es-ES', opciones);
        }

        // Funciones para gestionar noticias
        function actualizarTabla() {
            fetch("cargar_noticias.php")
                .then(response => response.json())
                .then(data => {
                    let tabla = document.getElementById('tablaNoticias');
                    tabla.innerHTML = '';  // Limpiar la tabla antes de agregar los nuevos datos

                    if (data.length === 0) {
                        tabla.innerHTML = '<tr><td colspan="6" class="text-center">No hay noticias disponibles.</td></tr>';
                        return;
                    }

                    let filas = '';
                    data.forEach(noticia => {
                        filas += `
                            <tr>
                                <td>${noticia.idnoticia}</td>
                                <td>${noticia.titulo}</td>
                                <td>${noticia.autor}</td>
                                <td>${formatearFecha(noticia.fecha)}</td>
                                <td><span class="badge bg-success">Publicada</span></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="editarNoticia(${noticia.idnoticia})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="showDeleteModal(${noticia.idnoticia})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" onclick="verNoticia(${noticia.idnoticia})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    tabla.innerHTML = filas;
                })
                .catch(error => {
                    console.error("Error al cargar noticias:", error);
                    document.getElementById('tablaNoticias').innerHTML = '<tr><td colspan="6" class="text-center text-danger">Hubo un error al cargar las noticias.</td></tr>';
                });
        }

        // Cambia el botón de publicar para que muestre el modal
        document.querySelector('.btn-success[onclick="agregarNoticia()"]').onclick = function() {
            // Validar formulario primero
            const form = document.getElementById('formulario');
            let isValid = true;

            // Validar campos requeridos
            if (!form.titulo.value.trim()) isValid = false;
            if (!form.fecha.value) isValid = false;
            if (!form.imagen.files[0]) isValid = false;
            if (!form.contenido.value.trim()) isValid = false;

            if (!isValid) {
        alert('Por favor complete todos los campos requeridos');
        return;
            }

            // Mostrar modal de confirmación
            const publishModal = new bootstrap.Modal(document.getElementById('publishModal'));
            publishModal.show();
        };

        // Confirmar publicación
        document.getElementById('confirmPublish').addEventListener('click', function() {
            const publishModal = bootstrap.Modal.getInstance(document.getElementById('publishModal'));
            publishModal.hide();

            agregarNoticia();
        });

                // Función modificada de agregarNoticia
                function agregarNoticia() {
                    let formData = new FormData(document.getElementById('formulario'));
                
                    fetch("guardar_noticia.php", { 
                        method: "POST", 
                        body: formData 
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            // Mostrar modal de éxito
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            document.getElementById('successMessage').textContent = "Noticia publicada correctamente";
                            successModal.show();
                        
                            // Resetear completamente el formulario
                            const form = document.getElementById('formulario');
                            form.reset();
                        
                            // Limpiar vista previa
                            document.getElementById('preview-titulo').textContent = "Título de la Noticia";
                            document.getElementById('preview-autor').textContent = "Autor";
                            document.getElementById('preview-fecha').textContent = "Fecha";
                            document.getElementById('preview-categoria').textContent = "Categoría";
                            document.getElementById('preview-contenido').textContent = "Contenido de la noticia aparecerá aquí...";
                            document.getElementById('preview-imagen').src = "";
                            document.getElementById('preview-imagen').style.display = 'none';
                            document.getElementById('noticia-preview').style.display = 'none';
                        
                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                successModal.hide();
                                mostrarSeccion('noticias');
                            }, 2000);
                        } else {
                            alert("Error al agregar noticia: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error al enviar datos:", error);
                        alert("Ocurrió un error al intentar publicar la noticia");
                    });
                }
            
                
                // Variable global para almacenar el ID de la noticia a eliminar
                let noticiaToDelete = null;             

                // Función para mostrar el modal de eliminación
                function showDeleteModal(id) {
                    noticiaToDelete = id;
                    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    deleteModal.show();
                }               

                // Confirmar eliminación
                document.getElementById('confirmDelete').addEventListener('click', function() {
                    if (noticiaToDelete) {
                        eliminarNoticia(noticiaToDelete);
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        deleteModal.hide();
                    }
                });             

                // Función modificada de eliminarNoticia
                function eliminarNoticia(id) {
                    fetch("eliminar_noticia.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "idnoticia=" + id
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            // Mostrar modal de éxito
                            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                            document.getElementById('successMessage').textContent = "Noticia eliminada correctamente";
                            successModal.show();

                            // Actualizar la tabla después de 1 segundo
                            setTimeout(() => {
                                successModal.hide();
                                actualizarTabla();
                            }, 1000);
                        } else {
                            alert("Error al eliminar noticia: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error al eliminar noticia:", error);
                        alert("Ocurrió un error al intentar eliminar la noticia");
                    });
                }
            
                function editarNoticia(id) { 
                    fetch("obtener_noticia.php?idnoticia=" + id)
                        .then(response => response.json())
                        .then(noticia => {
                            // Rellenar campos del formulario
                            document.getElementById("editId").value = noticia.idnoticia;
                            document.getElementById("editTitulo").value = noticia.titulo;
                            document.getElementById("editAutor").value = noticia.autor;
                            document.getElementById("editFecha").value = noticia.fecha;
                            document.getElementById("editContenido").value = noticia.informacion;
                        
                            // Mostrar imagen actual
                            const imgActual = document.getElementById("editImagenActual");
                            if (noticia.imagen && noticia.imagen.trim() !== "") {
                                imgActual.src = "uploads/" + noticia.imagen;
                                imgActual.alt = "Imagen actual de la noticia";
                                imgActual.style.display = "block";
                            } else {
                                imgActual.src = "https://via.placeholder.com/150x100?text=Sin+imagen";
                                imgActual.alt = "Sin imagen disponible";
                                imgActual.style.display = "block";
                            }
                        
                            // Preparar vista previa para nueva imagen
                            const imgPreview = document.getElementById("preview-imagen-edit");
                            imgPreview.src = "";
                            imgPreview.classList.add("d-none");
                        
                            const inputImagen = document.getElementById("editImagen");
                            inputImagen.value = ""; // Limpiar selección previa
                            inputImagen.onchange = function() {
                                const imagen = inputImagen.files[0];
                                if (imagen) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        imgPreview.src = e.target.result;
                                        imgPreview.classList.remove("d-none");
                                    };
                                    reader.readAsDataURL(imagen);
                                } else {
                                    imgPreview.classList.add("d-none");
                                    imgPreview.src = "";
                                }
                            };
                        
                            // Mostrar el modal
                            new bootstrap.Modal(document.getElementById("modalEditar")).show();
                        })
                        .catch(error => console.error("Error al obtener noticia:", error));
                }

        // Cambia el botón de guardar para que muestre el modal de confirmación
        document.querySelector('#modalEditar button[onclick="guardarEdicion()"]').onclick = function() {
            // Validar campos requeridos
            const titulo = document.getElementById('editTitulo').value.trim();
            const autor = document.getElementById('editAutor').value.trim();
            const fecha = document.getElementById('editFecha').value;
            const contenido = document.getElementById('editContenido').value.trim();
            
            if (!titulo || !fecha || !contenido) {
                alert('Por favor complete todos los campos requeridos');
                return;
            }
            
            // Mostrar modal de confirmación
            const saveModal = new bootstrap.Modal(document.getElementById('saveChangesModal'));
            saveModal.show();
        };
        
        // Confirmar guardar cambios
        document.getElementById('confirmSaveChanges').addEventListener('click', function() {
            const saveModal = bootstrap.Modal.getInstance(document.getElementById('saveChangesModal'));
            saveModal.hide();
            
            // Ejecutar la función de guardar
            guardarEdicion();
        });
        
        // Función modificada de guardarEdicion
        function guardarEdicion() {
            let formData = new FormData();
            formData.append("idnoticia", document.getElementById("editId").value);
            formData.append("titulo", document.getElementById("editTitulo").value);
            formData.append("autor", document.getElementById("editAutor").value);
            formData.append("fecha", document.getElementById("editFecha").value);
            formData.append("informacion", document.getElementById("editContenido").value);
        
            const imagenFile = document.getElementById("editImagen").files[0];
            if (imagenFile) {
                formData.append("imagen", imagenFile);
            }
        
            fetch("editar_noticia.php", { 
                method: "POST", 
                body: formData 
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    // Mostrar modal de éxito
                    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    document.getElementById('successMessage').textContent = "Cambios guardados correctamente";
                    successModal.show();
                    
                    // Actualizar la tabla después de 1 segundo
                    setTimeout(() => {
                        successModal.hide();
                        actualizarTabla();
                    }, 1000);
                    
                    // Cerrar el modal de edición
                    const editModal = bootstrap.Modal.getInstance(document.getElementById("modalEditar"));
                    editModal.hide();
                } else {
                    alert("Error al actualizar noticia: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error al actualizar noticia:", error);
                alert("Ocurrió un error al intentar guardar los cambios");
            });
        }
        
                function verNoticia(id) {
                    fetch('obtener_noticia.php?idnoticia=' + id)
                        .then(response => response.json())
                        .then(noticia => {
                            if (noticia && noticia.titulo) {
                                document.getElementById('vistaTitulo').textContent = noticia.titulo;
                                document.getElementById('vistaImagen').src = "uploads/" + noticia.imagen;
                                document.getElementById('vistaContenido').textContent = noticia.informacion;
                                document.getElementById('vistaFecha').textContent = formatearFecha(noticia.fecha);
                            
                                const vistaPreviaModal = new bootstrap.Modal(document.getElementById('modalVistaPrevia'));
                                vistaPreviaModal.show();
                            } else {
                                alert("No se encontró la noticia con ID: " + id);
                            }
                        })
                        .catch(error => {
                            console.error('Error al obtener la noticia:', error);
                        });
                }

        // Cerrar sesión con modal
        document.getElementById('btn-logout').addEventListener('click', function(e) {
            e.preventDefault();
            // Mostrar el modal en lugar del confirm nativo
            const logoutModal = new bootstrap.Modal(document.getElementById('logoutModal'));
            logoutModal.show();
        });

        // Confirmar cierre de sesión
        document.getElementById('confirmLogout').addEventListener('click', function() {
            // Hacer una petición al servidor para cerrar sesión
            fetch('logout.php', {
                method: 'POST'
            })
            .then(response => {
                if(response.ok) {
                    // Redirigir al login después de cerrar sesión
                    window.location.href = 'inicio_sesion.php';
                } else {
                    alert('Error al cerrar sesión');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

            // Cerrar el modal
            const logoutModal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
            logoutModal.hide();
        });
