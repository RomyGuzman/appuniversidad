// La función $(document).ready() asegura que todo el código JavaScript dentro de ella
// se ejecute solo después de que toda la página HTML (el DOM) se haya cargado por completo.
// Esto previene errores al intentar manipular elementos que aún no existen.

// --- INICIO: Inicialización de DataTables ---
if ($.fn.DataTable) {
    $('#careersTable').DataTable();
}
// --- FIN: Inicialización de DataTables ---

$(document).ready(function () {
    // Deshabilitar restauración de scroll para que al refrescar vaya al inicio
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    // Solo hacer scroll al inicio si no hay hash en la URL (evita scroll automático a secciones)
    if (!window.location.hash) {
        window.scrollTo(0, 0);
    }

    console.log('Script cargado'); // Agregado para depuración
    console.log('Elemento #ciencia-datos-link encontrado:', $('#ciencia-datos-link').length);

    // Lee la URL base de la aplicación desde un objeto global (window.APP_CONFIG)
    // que se define en las vistas PHP. Esto hace que las URLs de AJAX sean portátiles.
    const BASE_URL = window.APP_CONFIG.baseUrl;

    // --- Lógica para Estudiantes ---

    // Evento de clic para los botones de "Editar" en la tabla de estudiantes.
    // Usa delegación de eventos para funcionar incluso si la tabla se recarga.
    $('#studentsTable').on('click', '.edit-btn', function () {
        // Obtiene el ID del estudiante desde el atributo 'data-id' del botón.
        const studentId = $(this).data('id');

        // Petición AJAX para obtener los datos del estudiante desde el servidor.
        $.ajax({
            url: `${BASE_URL}estudiantes/edit/${studentId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) { // Se ejecuta si la petición es exitosa.
                // Rellena los campos del formulario del modal de edición con los datos recibidos.
                // CORREGIDO: Usar los nombres correctos de campos de la base de datos
                $('#edit_id').val(response.id);
                $('#edit_nest').val(response.nombre_estudiante);
                $('#edit_dni').val(response.dni);
                $('#edit_edad').val(response.edad);
                $('#edit_email').val(response.email);
                $('#edit_fecha_nac').val(response.fecha_nacimiento);
                $('#edit_carrera_id').val(response.carrera_id);
                // Actualiza la URL del 'action' del formulario para que apunte al método de actualización correcto.
                $('#editStudentForm').attr('action', `${BASE_URL}estudiantes/update/${studentId}`);
            },
            error: function() { // Se ejecuta si hay un error en la petición.
                Swal.fire('Error', 'No se pudieron cargar los datos del estudiante.', 'error');
            }
        });
    });

    // Evento de clic para los botones de "Eliminar" en la tabla de estudiantes.
    // Ahora intercepta el envío del formulario de borrado.
    $('body').on('submit', '.delete-form', function (e) {
        e.preventDefault(); // Previene el envío normal del formulario.
        const form = this; // 'this' es el formulario que se está enviando.
        // Llama a la función reutilizable que muestra la confirmación.
        showDeleteConfirmation(form);
    });

    // Evento de envío para el formulario de búsqueda de estudiante por ID.
    $('#searchStudentForm').on('submit', function(e) {
        e.preventDefault(); // Previene que el formulario se envíe y recargue la página.
        // Obtiene el ID del estudiante del campo de entrada.
        const studentId = $('#searchStudentId').val();
        // Si el campo está vacío, no hace nada.
        if (!studentId) return;

        // Petición AJAX para buscar al estudiante.
        $.ajax({
            url: `${BASE_URL}estudiantes/search/${studentId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // CORREGIDO: Usar los nombres correctos de campos de la base de datos
                $('#detailId').text(response.id);
                $('#detailName').text(response.nombre_estudiante);
                $('#detailDni').text(response.dni);
                $('#detailCareer').text(response.nombre_carrera || 'No asignada');
                // Muestra el contenedor de detalles que estaba oculto.
                $('#studentDetails').removeClass('d-none');
            },
            error: function(xhr) {
                // Muestra un mensaje de error si el estudiante no encuentra.
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Error al buscar.';
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    });

    // Evento de clic para el botón "Limpiar" en la búsqueda por ID.
    $('#clearStudentDetails').on('click', () => {
        $('#studentDetails').addClass('d-none');
        $('#searchStudentId').val(''); // Limpiar también el campo de entrada
    });

    // --- Lógica para Profesores ---

    // Evento de clic para los botones de "Editar" en la tabla de profesores.
    $('#profsTable').on('click', '.edit-btn', function () {
        // Obtiene el ID del profesor desde el atributo 'data-id' del botón.
        const profId = $(this).data('id');

        // Petición AJAX para obtener los datos del profesor desde el servidor.
        $.ajax({
            url: `${BASE_URL}profesores/edit/${profId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) { // Se ejecuta si la petición es exitosa.
                // Rellena los campos del formulario del modal de edición con los datos recibidos.
                $('#edit_id').val(response.id);
                $('#edit_nombre_profesor').val(response.nombre_profesor);
                $('#edit_legajo').val(response.legajo);
                // Actualiza la URL del 'action' del formulario para que apunte al método de actualización correcto.
                $('#editProfForm').attr('action', `${BASE_URL}profesores/update/${profId}`);
            },
            error: function() { // Se ejecuta si hay un error en la petición.
                Swal.fire('Error', 'No se pudieron cargar los datos del profesor.', 'error');
            }
        });
    });

    // Evento de envío para el formulario de búsqueda de profesor por ID.
    $('#searchProfForm').on('submit', function(e) {
        e.preventDefault(); // Previene que el formulario se envíe y recargue la página.
        // Obtiene el ID del profesor del campo de entrada.
        const profId = $('#searchProfId').val();
        // Si el campo está vacío, no hace nada.
        if (!profId) return;

        // Petición AJAX para buscar al profesor.
        $.ajax({
            url: `${BASE_URL}profesores/search/${profId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#detailId').text(response.id);
                $('#detailLegajo').text(response.legajo);
                $('#detailName').text(response.nombre_profesor);
                // Muestra el contenedor de detalles que estaba oculto.
                $('#profDetails').removeClass('d-none');
            },
            error: function(xhr) {
                // Muestra un mensaje de error si el profesor no se encuentra.
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Error al buscar.';
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    });

    // Evento de clic para el botón "Limpiar" en la búsqueda por ID de profesor.
    $('#clearProfDetails').on('click', () => $('#profDetails').addClass('d-none'));

    // Evento de envío para el formulario de búsqueda de profesor por legajo.
    $('#searchProfByLegajoForm').on('submit', function(e) {
        e.preventDefault(); // Previene que el formulario se envíe y recargue la página.
        // Obtiene el legajo del profesor del campo de entrada.
        const profLegajo = $('#searchProfLegajo').val();
        // Si el campo está vacío, no hace nada.
        if (!profLegajo) return;

        // Petición AJAX para buscar al profesor por legajo.
        $.ajax({
            url: `${BASE_URL}profesores/searchByLegajo/${profLegajo}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#detailIdByLegajo').text(response.id);
                $('#detailLegajoByLegajo').text(response.legajo);
                $('#detailNameByLegajo').text(response.nombre_profesor);
                // Muestra el contenedor de detalles que estaba oculto.
                $('#profDetailsByLegajo').removeClass('d-none');
            },
            error: function(xhr) {
                // Muestra un mensaje de error si el profesor no se encuentra.
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Error al buscar.';
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    });

    // --- Lógica para Usuarios ---

    // Evento de clic para los botones de "Editar" en la tabla de usuarios.
    $('#usuariosTable').on('click', '.edit-btn', function () {
        // Obtiene el ID del usuario desde el atributo 'data-id' del botón.
        const usuarioId = $(this).data('id');

        // Petición AJAX para obtener los datos del usuario desde el servidor.
        $.ajax({
            url: `${BASE_URL}usuarios/edit/${usuarioId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) { // Se ejecuta si la petición es exitosa.
                // Rellena los campos del formulario del modal de edición con los datos recibidos.
                $('#edit_id').val(response.id);
                $('#edit_usuario').val(response.usuario);
                $('#edit_rol_id').val(response.rol_id);
                $('#edit_activo').prop('checked', response.activo == 1);
                // Actualiza la URL del 'action' del formulario para que apunte al método de actualización correcto.
                $('#editUsuarioForm').attr('action', `${BASE_URL}usuarios/update/${usuarioId}`);
            },
            error: function() { // Se ejecuta si hay un error en la petición.
                Swal.fire('Error', 'No se pudieron cargar los datos del usuario.', 'error');
            }
        });
    });

    // Evento de clic para el botón "Limpiar" en la búsqueda por legajo de profesor.
    $('#clearProfDetailsByLegajo').on('click', () => $('#profDetailsByLegajo').addClass('d-none'));

    // Evento de envío para el formulario de búsqueda de estudiantes por carrera.
    $('#searchStudentByCareerForm').on('submit', function(e) {
        e.preventDefault();
        // Obtiene el ID de la carrera seleccionada en el menú desplegable.
        const careerId = $('#searchCareer').val();
        if (!careerId) {
            // Muestra una advertencia si no se ha seleccionado ninguna carrera.
            Swal.fire('Atención', 'Por favor, seleccione una carrera.', 'warning');
            return;
        }

        $.ajax({
            url: `${BASE_URL}estudiantes/search/carrera/${careerId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const resultsContainer = $('#studentsByCareerResults');
                const clearBtnContainer = $('#clearCareerResultsContainer');
                resultsContainer.empty(); // Limpia cualquier resultado de búsqueda anterior.

                // Si la respuesta contiene estudiantes, los recorre y crea una tarjeta para cada uno.
                if (response.length > 0) {
                    response.forEach(student => {
                        const studentCard = `
                            <div class="col-md-6">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${student.nombre_estudiante}</h5>
                                        <p class="card-text mb-1"><strong>ID:</strong> ${student.id}</p>
                                        <p class="card-text mb-1"><strong>DNI:</strong> ${student.dni}</p>
                                        <p class="card-text"><strong>Carrera:</strong> ${student.nombre_carrera || 'No asignada'}</p>
                                    </div>
                                </div>
                            </div>`;
                        resultsContainer.append(studentCard);
                    });
                } else {
                    // Si no se encuentran estudiantes, muestra un mensaje informativo.
                    resultsContainer.html('<div class="col-12"><p class="text-center text-muted">No se encontraron estudiantes en esta carrera.</p></div>');
                }
                // Muestra el contenedor del botón "Limpiar Búsqueda".
                clearBtnContainer.removeClass('d-none');
            }
        });
    });

    // Evento de clic para el botón "Limpiar Búsqueda" de la búsqueda por carrera.
    $('#clearCareerResultsBtn').on('click', function() {
        $('#studentsByCareerResults').empty(); // Vacía el contenedor de resultados.
        $('#clearCareerResultsContainer').addClass('d-none'); // Oculta el botón de limpiar.
        $('#searchCareer').val(''); // Opcional: resetea el menú desplegable a su estado inicial.
    });

    // --- Lógica para generar código de carrera en tiempo real ---
    let debounceTimer;
    $('#registerName').on('input', function() {
        // Limpia el temporizador anterior cada vez que se presiona una tecla.
        clearTimeout(debounceTimer);

        const nombreCarrera = $(this).val().trim();
        const careerCodeInput = $('#careerCode');

        if (nombreCarrera.length < 3) {
            careerCodeInput.val(''); // Limpia el campo si el nombre es muy corto
            return;
        }

        // Configura un nuevo temporizador. El código se ejecutará 500ms después de que el usuario deje de escribir.
        // Esto evita hacer una llamada AJAX en cada pulsación de tecla (debounce).
        debounceTimer = setTimeout(() => {
            // Codifica el nombre para que sea seguro en una URL
            const nombreCodificado = encodeURIComponent(nombreCarrera);

            $.ajax({
                url: `${BASE_URL}carreras/generar-codigo/${nombreCodificado}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.codigo) {
                        careerCodeInput.val(response.codigo);
                    }
                },
                error: function() {
                    // No hacer nada si hay un error, para no confundir al usuario.
                    console.error('Error al generar el código de carrera.');
                }
            });
        }, 500);
    });

    // --- Lógica para generar código de materia en tiempo real ---
    function generarCodigoMateria() {
        // Limpia el temporizador anterior cada vez que se presiona una tecla.
        clearTimeout(debounceTimer);

        const nombreMateria = $('#nombre_materia').val().trim();
        const materiaCodeInput = $('#codigo_materia');

        if (nombreMateria.length < 3) {
            materiaCodeInput.val(''); // Limpia el campo si el nombre es muy corto
            return;
        }

        // Configura un nuevo temporizador. El código se ejecutará 300ms después de que el usuario deje de escribir.
        // Esto evita hacer una llamada AJAX en cada pulsación de tecla (debounce).
        debounceTimer = setTimeout(() => {
            // Codifica el nombre para que sea seguro en una URL
            const nombreCodificado = encodeURIComponent(nombreMateria);

            $.ajax({
                url: `${BASE_URL}materias/generar-codigo/${nombreCodificado}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.codigo) {
                        materiaCodeInput.val(response.codigo);
                    }
                },
                error: function() {
                    // No hacer nada si hay un error, para no confundir al usuario.
                    console.error('Error al generar el código de materia.');
                }
            });
        }, 300); // Reducido a 300ms para respuesta más rápida
    }

    // Evento único para generar código de materia (captura tanto escritura como pegado)
    $('#nombre_materia').on('input change paste', function() {
        console.log('Evento input/change/paste detectado en nombre_materia');
        generarCodigoMateria();
    });

    // --- Lógica para Carreras ---
    // La lógica para Carreras y Categorías sigue el mismo patrón que la de Estudiantes:
    // 1. Evento para el botón de editar (AJAX para llenar el modal).
    // 2. Evento para el botón de eliminar (llama a la confirmación).
    // 3. Evento para el formulario de búsqueda por ID (AJAX para mostrar detalles).
    $('#careersTable').on('click', '.edit-car-btn', function() {
        const careerId = $(this).data('id');

        $.ajax({
            url: `${BASE_URL}carreras/edit/${careerId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // CORRECCIÓN: Se usan los nombres de columna correctos de la BD ('id', 'nombre_carrera', etc.)
                $('#edit_id_car').val(response.id);
                $('#edit_ncar').val(response.nombre_carrera);
                $('#edit_codcar').val(response.codigo_carrera);
                $('#edit_duracion').val(response.duracion);
                $('#edit_id_cat').val(response.id_categoria);
                $('#edit_modalidad').val(response.id_modalidad);
                $('#edit_id_persona').val(response.id_persona);
                $('#editCareerForm').attr('action', `${BASE_URL}carreras/update/${careerId}`);
            },
            error: function() {
                Swal.fire('Error', 'No se pudieron cargar los datos de la carrera.', 'error');
            }
        });
    });

    // --- Lógica para Categorías ---
    $('#categoriesTable').on('click', '.edit-cat-btn', function() {
        const categoryId = $(this).data('id');

        $.ajax({
            url: `${BASE_URL}categorias/edit/${categoryId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#edit_id_cat').val(response.id_cat);
                $('#edit_ncat').val(response.ncat);
                $('#edit_codcat').val(response.codcat);
                $('#editCategoryForm').attr('action', `${BASE_URL}categorias/update/${categoryId}`);
            },
            error: function() {
                Swal.fire('Error', 'No se pudieron cargar los datos de la categoría.', 'error');
            }
        });
    });

    $('#searchCategoryForm').on('submit', function(e) {
        e.preventDefault();
        const categoryId = $('#searchCategoryId').val();
        if (!categoryId) return;

        $.ajax({
            url: `${BASE_URL}categorias/search/${categoryId}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#detailCategoryId').text(response.id_cat);
                $('#detailCategoryName').text(response.ncat);
                $('#categoryDetails').removeClass('d-none');
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Error al buscar.';
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    });

    $('#clearCategoryDetails').on('click', () => $('#categoryDetails').addClass('d-none'));

    // Búsqueda de carrera por ID
    $('#searchCareerForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#searchCareerId').val().trim();
        if (!id || isNaN(id) || parseInt(id) <= 0) {
            $('#getResult').html('<div class="alert alert-warning">Por favor ingrese un ID válido (número positivo).</div>');
            return;
        }
        $.ajax({
            url: `${BASE_URL}carreras/search/${id}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                let html = '<div class="alert alert-success mb-3">Carrera encontrada:</div>';
                html += '<div class="row">';
                html += '<div class="col-md-6">';
                html += '<ul class="list-group">';
                html += `<li class="list-group-item"><strong>ID:</strong> ${response.id || 'N/A'}</li>`;
                html += `<li class="list-group-item"><strong>Nombre:</strong> ${response.nombre_carrera || 'N/A'}</li>`;
                html += `<li class="list-group-item"><strong>Código:</strong> ${response.codigo_carrera || 'N/A'}</li>`;
                html += '</ul>';
                html += '</div>';
                html += '<div class="col-md-6">';
                html += '<ul class="list-group">';
                html += `<li class="list-group-item"><strong>Categoría:</strong> ${response.nombre_categoria || 'No asignada'}</li>`;
                html += `<li class="list-group-item"><strong>Modalidad:</strong> ${response.nombre_modalidad || 'No asignada'}</li>`;
                html += `<li class="list-group-item"><strong>Estado:</strong> Activa</li>`;
                html += '</ul>';
                html += '</div>';
                html += '</div>';
                html += '<div class="text-center mt-3">';
                html += '<button id="clearCareerSearch" class="btn btn-secondary"><i class="fas fa-times me-2"></i>Limpiar</button>';
                html += '</div>';
                $('#getResult').html(html);
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Error desconocido';
                $('#getResult').html(`<div class="alert alert-danger">${errorMsg}</div>`);
            }
        });
    });

    // Limpiar búsqueda de carrera
    $('body').on('click', '#clearCareerSearch', function() {
        $('#getResult').html('');
        $('#searchCareerId').val('');
    });


    // --- Funciones reutilizables ---

    /**
     * Muestra una ventana de confirmación de SweetAlert antes de realizar una acción de borrado.
     * @param {HTMLFormElement} form - El formulario que se enviará si el usuario confirma.
     */
    function showDeleteConfirmation(form) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Envía el formulario si el usuario confirma.
            }
        });
    }

    /**
     * =================================================================================
     * LÓGICA UNIFICADA PARA CARGA DINÁMICA DE CONTENIDO (SPA)
     * =================================================================================
     * Esta sección maneja la carga de vistas parciales de las carreras sin recargar
     * la página, creando una experiencia de Single-Page Application.
     */

    /**
     * Función principal y reutilizable para cargar contenido de carreras vía AJAX.
     * @param {string} url - La URL relativa del endpoint en AjaxController (ej: 'ajax/ciencia_datos').
     * @param {string} [containerSelector='#careers'] - El selector del div que se actualizará.
     */
    function cargarContenidoCarrera(url, containerSelector = '#careers') {
        const contentContainer = $(containerSelector);
        if (contentContainer.length === 0) {
            console.error('El contenedor para la carga dinámica no fue encontrado:', containerSelector);
            return;
        }

        // --- LÓGICA PARA GESTIONAR LA VISIBILIDAD DE "QUIÉNES SOMOS" ---
        // Si la URL que se carga es la de registro, oculta la sección "Quiénes Somos".
        // Para cualquier otra URL, se asegura de que esté visible.
        const aboutSection = $('#about');
        if (url === 'ajax/registro') {
            aboutSection.slideUp(200); // Oculta la sección con una animación suave.
        } else {
            aboutSection.slideDown(200); // Muestra la sección si estaba oculta.
        }

        // 1. Añade una clase para feedback visual y hace un fadeOut del contenido actual
        contentContainer.addClass('loading-content');
        contentContainer.fadeOut(200, function() {
            // 2. Realiza la petición AJAX
            $.ajax({
                url: `${BASE_URL}${url}`, // Construye la URL completa usando la variable global
                type: 'GET',
                success: function(response) {
                    let finalHtml = response;

                    // 3. Añade un botón "Volver" si no estamos cargando la vista por defecto
                    //    Y TAMPOCO si estamos en la vista de registro.
                    if (url !== 'ajax/oferta_academica_default' && url !== 'ajax/registro') {
                        const volverBtnHtml = `
                            <div class="container mt-4 text-center" data-aos="fade-up">
                                <a href="#" id="volver-oferta-default" class="btn btn-secondary btn-lg px-4 py-2">
                                    <i class="fas fa-arrow-left me-2"></i>Volver a la Oferta Principal
                                </a>
                            </div>
                        `;
                        finalHtml += volverBtnHtml;
                    }

                    // 4. Inyecta el nuevo HTML y lo muestra con un fadeIn
                    contentContainer.html(finalHtml).removeClass('loading-content').fadeIn(300);

                    // 5. Re-inicializa las animaciones AOS para el nuevo contenido cargado
                    if (typeof AOS !== 'undefined') {
                        AOS.init({
                            once: true
                        });
                    }

                    // 6. (NUEVO) Desplaza suavemente la vista hasta el contenedor del nuevo contenido
                    // Solo se ejecuta si no estamos volviendo a la vista por defecto (para evitar un salto innecesario).
                    if (url !== 'ajax/oferta_academica_default') {
                        $('html, body').animate({
                            scrollTop: contentContainer.offset().top - 80 // Restamos 80px para compensar la altura del navbar
                        }, 800); // 800ms de duración para un scroll suave
                    }
                },
                error: function(xhr, status, error) {
                    // 6. En caso de error, muestra un mensaje claro
                    // ==================================================================
                    // INICIO: LOG DE DEPURACIÓN PARA VER EL ERROR DEL SERVIDOR
                    // ==================================================================
                    console.log('¡ERROR AJAX DETECTADO! La URL solicitada fue:', `${BASE_URL}${url}`);
                    console.error('Respuesta completa del servidor (esto nos dirá el error exacto):', xhr.responseText);
                    // ==================================================================
                    contentContainer.html('<div class="alert alert-danger text-center">Error al cargar el contenido. Por favor, intente de nuevo.</div>').removeClass('loading-content').fadeIn(300);
                }
            });
        });
    }

    // --- MANEJADORES DE EVENTOS UNIFICADOS ---

    // MANEJADOR UNIFICADO PARA TODA LA NAVEGACIÓN DINÁMICA DE LA OFERTA ACADÉMICA
    // Este manejador captura clics en:
    // 1. Enlaces de carreras en el navbar (id termina en "-link").
    // 2. Botones "Ver detalle" en las tarjetas (id empieza con "ver-detalle-").
    // 3. Botones "Inscribite ahora" (clase ".btn-inscribir").
    $('body').on('click', 'a[id$="-link"], a[id^="ver-detalle-"], .btn-inscribir, #volver-oferta-default', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let url;
        // Si el botón tiene la clase .btn-inscribir, la URL es siempre la de registro.
        if ($(this).hasClass('btn-inscribir')) {
            url = 'ajax/registro';
        // Si es el botón de volver, carga la vista por defecto.
        } else if (this.id === 'volver-oferta-default') {
            url = 'ajax/oferta_academica_default';
        } else {
            // Si no, es un enlace de carrera. Construimos la URL a partir del ID.
            // SOLUCIÓN PRECISA:
            // 1. Limpiamos el ID para obtener el slug base (ej: "ciencia-datos").
            const slugBase = this.id.replace('-link', '').replace('ver-detalle-', '');
            // 2. Reemplazamos TODOS los guiones por guiones bajos para que coincida con la ruta.
            const slugFinal = slugBase.replace(/-/g, '_');
            url = `ajax/${slugFinal}`; // URL final: "ajax/ciencia_datos"
        }
        
        // Llama a la función de carga con la URL correcta
        cargarContenidoCarrera(url);

        // Cierra el menú desplegable del navbar en móviles si está abierto
        $('.navbar-collapse').collapse('hide');

        // =============================================================================
        // SOLUCIÓN FINAL Y DEFINITIVA (MANIPULACIÓN DIRECTA)
        // Esto cierra el menú desplegable de "Oferta Académica" en todas las pantallas
        // forzando la eliminación de las clases y atributos que lo mantienen abierto.
        // =============================================================================
        $('.dropdown-menu.show').removeClass('show');
        $('.dropdown-toggle[aria-expanded="true"]').attr('aria-expanded', 'false');
    });

    // Comprueba si existen mensajes "flash" de éxito o error pasados desde el controlador PHP.
    // Estos mensajes se usan para notificar al usuario el resultado de una acción (ej: "Estudiante registrado").
    // Si encuentra un mensaje, lo muestra con SweetAlert.
    if (window.APP_CONFIG.flash.success) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            html: window.APP_CONFIG.flash.success,
            showConfirmButton: false,
            timer: 2000
        });
    }

    if (window.APP_CONFIG.flash.error) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: window.APP_CONFIG.flash.error
        });
    }

    // Lógica para el botón de toggle test
    let ajaxTestLoaded = false;
    $('#toggle-test-btn').on('click', function() {
        $('#ajax-test-section').slideToggle();
        if (!ajaxTestLoaded) {
            $.get(`${BASE_URL}ajax/test`, function(data) {
                $('#ajax-test-content').html(data);
                ajaxTestLoaded = true;
            });
        }
    });

    // --- Lógica para selección de carrera en página de materias ---
    $('#carreraSelect').on('change', function() {
        const selectedCarreraId = $(this).val();
        const url = `${BASE_URL}administrador/materias${selectedCarreraId ? '?carrera_id=' + selectedCarreraId : ''}`;
        window.location.href = url;
    });

    // --- Lógica para confirmación de cierre de sesión ---
    // Usa delegación de eventos para funcionar en cualquier navbar
    $('body').on('click', '.logout-btn', function(e) {
        e.preventDefault(); // Previene la navegación inmediata
        const href = $(this).attr('href'); // Obtiene la URL del enlace

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Estás a punto de cerrar tu sesión.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href; // Navega si el usuario confirma
            }
        });
    });

    // --- Lógica para el Sistema de Alertas del Administrador ---

    // Función para actualizar el contador de alertas
    function actualizarContadorAlertas() {
        // Solo ejecuta si el contador existe en la página
        if ($('#alerta-contador').length) {
            $.ajax({
                url: `${BASE_URL}administrador/alertas/count`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const contador = $('#alerta-contador');
                    const count = response.unread_count;

                    if (count > 0) {
                        contador.text(count).removeClass('d-none');
                    } else {
                        contador.addClass('d-none');
                    }
                },
                error: function() {
                    console.error('Error al obtener el número de alertas.');
                }
            });
        }
    }

    // Llama a la función al cargar la página del admin
    actualizarContadorAlertas();

    // (Opcional) Actualiza el contador cada 60 segundos
    setInterval(actualizarContadorAlertas, 60000);

    // Manejador para el botón "Resolver" en la tabla de alertas
    $('body').on('click', '.mark-as-read-btn', function() {
        const boton = $(this);
        const consultaId = boton.data('id');

        // Usamos SweetAlert para una mejor experiencia de usuario
        Swal.fire({
            title: '¿Marcar como resuelta?',
            text: "Esta acción cambiará el estado de la consulta.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, resolver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Preparamos los datos a enviar, incluyendo el token CSRF
                // Obtenemos el nombre y el valor del token desde los campos ocultos que CI genera
                const csrfName = $('input[name=csrf_test_name]').attr('name');
                const csrfHash = $('input[name=csrf_test_name]').val();
                let postData = {
                    [csrfName]: csrfHash
                };

                $.ajax({
                    url: `${BASE_URL}administrador/alertas/mark-as-read/${consultaId}`,
                    type: 'POST',
                    data: postData, // Enviamos el token CSRF
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // --- Lógica para actualizar la TABLA ---
                            const fila = $(`#consulta-row-${consultaId}`);

                            // 1. Cambiar el estilo de la fila
                            fila.addClass('table-secondary text-muted').removeClass('border-left-warning').addClass('border-left-secondary');

                            // 2. Actualizar la celda de estado con el tilde verde
                            const estadoCell = fila.find('td:first-child');
                            estadoCell.html('<i class="fas fa-check-circle text-success" title="Resuelta"></i>');

                            // 3. Eliminar el botón "Resolver"
                            boton.remove();

                            // 4. Actualizar el contador de alertas en el navbar
                            actualizarContadorAlertas();

                            // Actualizar el valor del token CSRF para la siguiente petición
                            if (response.csrf_hash) {
                                $('input[name=csrf_test_name]').val(response.csrf_hash);
                            }
                        } else {
                            Swal.fire('Error', response.error || 'No se pudo resolver la consulta.', 'error');
                            // Si el error es por CSRF, recargamos para obtener un nuevo token
                            if (response.error && response.error.includes('CSRF')) {
                                setTimeout(() => location.reload(), 2000);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error AJAX:', xhr.responseText, status, error);
                        Swal.fire('Error', 'Ocurrió un problema de comunicación con el servidor.', 'error');
                    }
                });
            }
        });
    });

    // --- Lógica para el Modal de Detalles de Alerta ---
    $('body').on('click', '.view-details-btn', function() {
        const boton = $(this);
        
        // Obtener datos desde los atributos data-* del botón
        const asunto = boton.data('asunto');
        const mensaje = boton.data('mensaje');
        const remitente = boton.data('remitente');
        const email = boton.data('email');
        const fecha = boton.data('fecha');

        // Rellenar el contenido del modal
        $('#modal-asunto').text(asunto);
        $('#modal-mensaje').text(mensaje);
        $('#modal-remitente').text(remitente);
        $('#modal-email').text(email);
        $('#modal-fecha').text(fecha);
    });

    // **********************************************************************************************************************
    // _asistencia_table.php (es el js)
   //*************************************************************************** */





   // Inicializar todas las tablas de asistencia en la página
    const attendanceContainers = document.querySelectorAll('.attendance-container');
    attendanceContainers.forEach(container => {
        const materiaId = container.dataset.materiaId;
        if (materiaId) {
            initializeAttendanceTable(parseInt(materiaId, 10));
        }
    });

});

function initializeAttendanceTable(materiaId) {
    // Contenedor principal para esta instancia de la tabla
    const container = document.querySelector(`.attendance-container[data-materia-id="${materiaId}"]`);
    if (!container) return;

    // Elementos del DOM
    const monthSelect = container.querySelector('.month-select');
    const yearSelect = container.querySelector('.year-select');
    const generateBtn = container.querySelector('.generate-btn');
    const attendanceTable = container.querySelector('.attendance-table');
    const saveBtn = container.querySelector('.save-btn');
    const resetBtn = container.querySelector('.reset-btn');
    const markAllPresentBtn = container.querySelector('.mark-all-present-btn');
    const markAllAbsentBtn = container.querySelector('.mark-all-absent-btn');
    const monthYearDisplay = container.querySelector('.month-year-display');
    const statsContainer = container.querySelector('.stats');

    // Datos de ejemplo (estudiantes) - En producción, estos vendrían del servidor
    const students = [
        { id: 1, name: "Ana García" },
        { id: 2, name: "Carlos López" },
        { id: 3, name: "María Rodríguez" },
        { id: 4, name: "José Martínez" },
        { id: 5, name: "Laura Sánchez" },
        { id: 6, name: "Miguel Pérez" },
        { id: 7, name: "Elena Díaz" },
        { id: 8, name: "David Ruiz" }
    ];

    // Nombres de los días de la semana
    const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

    // Nombres de los meses
    const monthNames = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    // Objeto para almacenar el estado de las asistencias
    let attendanceState = {};

    // Inicializar años en el selector (desde 2020 hasta 2030)
    function initializeYearSelect() {
        const currentYear = new Date().getFullYear();
        const currentMonth = new Date().getMonth();

        // Establecer el mes actual como seleccionado por defecto
        monthSelect.value = currentMonth;

        for (let year = 2020; year <= 2030; year++) {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (year === currentYear) {
                option.selected = true;
            }
            yearSelect.appendChild(option);
        }
    }

    // Obtener el número de días en un mes específico
    function getDaysInMonth(month, year) {
        return new Date(year, month + 1, 0).getDate();
    }

    // Obtener el día de la semana para un día específico
    function getDayOfWeek(month, year, day) {
        return new Date(year, month, day).getDay();
    }

    // Obtener clave única para almacenar el estado de asistencia
    function getAttendanceKey(personId, day, month, year) {
        return `${year}-${month}-${day}-${personId}`;
    }

    // Obtener el estado guardado de una asistencia
    function getSavedAttendance(personId, day, month, year) {
        const key = getAttendanceKey(personId, day, month, year);
        return attendanceState[key];
    }

    // Guardar el estado de una asistencia
    function saveAttendance(personId, day, month, year, isPresent) {
        const key = getAttendanceKey(personId, day, month, year);
        attendanceState[key] = isPresent;
    }

    // Calcular el porcentaje de asistencia para una persona
    function calculatePercentage(personId, daysInMonth, month, year) {
        let presentCount = 0;

        for (let day = 1; day <= daysInMonth; day++) {
            const isPresent = getSavedAttendance(personId, day, month, year);
            if (isPresent === true) {
                presentCount++;
            }
        }

        return ((presentCount / daysInMonth) * 100).toFixed(1);
    }

    // Generar la tabla de asistencias
    function generateAttendanceTable() {
        const month = parseInt(monthSelect.value);
        const year = parseInt(yearSelect.value);

        // Actualizar el display del mes y año
        monthYearDisplay.textContent = `${monthNames[month]} ${year}`;

        // Limpiar tabla existente
        attendanceTable.innerHTML = '';

        // Crear encabezado de la tabla
        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');

        // Celda para el encabezado de alumnos
        const personHeader = document.createElement('th');
        personHeader.textContent = 'Alumno';
        personHeader.className = 'person-header';
        headerRow.appendChild(personHeader);

        // Generar encabezados para cada día del mes
        const daysInMonth = getDaysInMonth(month, year);

        for (let day = 1; day <= daysInMonth; day++) {
            const dayHeader = document.createElement('th');
            dayHeader.className = 'day-header';

            // Crear contenedor de información del día
            const dayInfo = document.createElement('div');
            dayInfo.className = 'day-info';

            // Número del día
            const dayNumber = document.createElement('div');
            dayNumber.className = 'day-number';
            dayNumber.textContent = day;
            dayInfo.appendChild(dayNumber);

            // Nombre del día
            const dayName = document.createElement('div');
            dayName.className = 'day-name';
            const dayOfWeek = getDayOfWeek(month, year, day);
            dayName.textContent = dayNames[dayOfWeek];
            dayInfo.appendChild(dayName);

            dayHeader.appendChild(dayInfo);

            // Determinar si es fin de semana
            if (dayOfWeek === 0 || dayOfWeek === 6) {
                dayHeader.classList.add('weekend');
            }

            headerRow.appendChild(dayHeader);
        }

        // Agregar columna de porcentaje
        const percentageHeader = document.createElement('th');
        percentageHeader.textContent = '% Asist.';
        percentageHeader.style.backgroundColor = '#2980b9';
        percentageHeader.style.minWidth = '60px';
        headerRow.appendChild(percentageHeader);

        thead.appendChild(headerRow);
        attendanceTable.appendChild(thead);

        // Crear cuerpo de la tabla
        const tbody = document.createElement('tbody');

        // Generar filas para cada alumno
        students.forEach(student => {
            const row = document.createElement('tr');
            row.className = 'person-row';

            // Celda con el nombre del alumno
            const nameCell = document.createElement('td');
            nameCell.textContent = student.name;
            nameCell.className = 'person-name';
            row.appendChild(nameCell);

            // Generar celdas de asistencia para cada día
            for (let day = 1; day <= daysInMonth; day++) {
                const attendanceCell = document.createElement('td');
                attendanceCell.className = 'attendance-cell';

                // Determinar si es fin de semana
                const dayOfWeek = getDayOfWeek(month, year, day);
                if (dayOfWeek === 0 || dayOfWeek === 6) {
                    attendanceCell.classList.add('weekend');
                }

                const checkboxContainer = document.createElement('div');
                checkboxContainer.className = 'checkbox-container';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'attendance-checkbox';
                checkbox.dataset.personId = student.id;
                checkbox.dataset.day = day;

                // Obtener estado guardado o usar false como predeterminado
                const savedState = getSavedAttendance(student.id, day, month, year);
                checkbox.checked = savedState === undefined ? false : savedState;

                // Agregar evento para guardar estado y actualizar estadísticas
                checkbox.addEventListener('change', function() {
                    saveAttendance(student.id, day, month, year, checkbox.checked);
                    updateStats();
                    updatePercentage(student.id, daysInMonth, month, year);
                });

                checkboxContainer.appendChild(checkbox);
                attendanceCell.appendChild(checkboxContainer);
                row.appendChild(attendanceCell);
            }

            // Celda de porcentaje para el alumno
            const percentageCell = document.createElement('td');
            percentageCell.className = 'percentage-cell'; // La clase ya existe, pero el ID debe ser único
            percentageCell.id = `percentage-${materiaId}-${student.id}`;
            percentageCell.textContent = '0%';
            row.appendChild(percentageCell);

            tbody.appendChild(row);
        });

        attendanceTable.appendChild(tbody);

        // Actualizar estadísticas y porcentajes
        updateStats();
        updateAllPercentages(daysInMonth, month, year);
    }

    // Actualizar el porcentaje de un alumno específico
    function updatePercentage(personId, daysInMonth, month, year) {
        const percentage = calculatePercentage(personId, daysInMonth, month, year);
        const percentageCell = container.querySelector(`#percentage-${materiaId}-${personId}`);
        percentageCell.textContent = `${percentage}%`;

        // Color según el porcentaje
        if (percentage >= 90) {
            percentageCell.style.color = '#2ecc71';
        } else if (percentage >= 70) {
            percentageCell.style.color = '#f39c12';
        } else {
            percentageCell.style.color = '#e74c3c';
        }
    }

    // Actualizar todos los porcentajes
    function updateAllPercentages(daysInMonth, month, year) {
        students.forEach(student => {
            updatePercentage(student.id, daysInMonth, month, year);
        });
    }

    // Actualizar estadísticas
    function updateStats() {
        const month = parseInt(monthSelect.value);
        const year = parseInt(yearSelect.value);
        const daysInMonth = getDaysInMonth(month, year);
        const totalCheckboxes = students.length * daysInMonth;

        // Contar asistencias desde el estado guardado
        let presentCount = 0;
        let absentCount = 0;

        students.forEach(student => {
            for (let day = 1; day <= daysInMonth; day++) {
                const isPresent = getSavedAttendance(student.id, day, month, year);
                if (isPresent === true) {
                    presentCount++;
                } else {
                    absentCount++;
                }
            }
        });

        // Calcular porcentajes
        const presentPercentage = ((presentCount / totalCheckboxes) * 100).toFixed(1);
        const absentPercentage = ((absentCount / totalCheckboxes) * 100).toFixed(1);

        // Actualizar estadísticas en el DOM
        statsContainer.innerHTML = `
            <div class="stat-item">
                <div class="stat-value">${presentCount}</div>
                <div class="stat-label">Días Presentes</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${absentCount}</div>
                <div class="stat-label">Días Ausentes</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${presentPercentage}%</div>
                <div class="stat-label">Asistencia General</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${students.length}</div>
                <div class="stat-label">Total Alumnos</div>
            </div>
        `;
    }

    // Guardar asistencias con SweetAlert
    async function saveAttendances() {
        const result = await Swal.fire({
            title: '¿Guardar asistencias?',
            text: '¿Está seguro que desea guardar todas las asistencias?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, guardar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2ecc71',
            cancelButtonColor: '#e74c3c'
        });

        if (result.isConfirmed) {
            // En una aplicación real, aquí enviaríamos los datos al servidor
            console.log('Asistencias guardadas:', attendanceState);

            await Swal.fire({
                title: '¡Guardado!',
                text: 'Las asistencias se han guardado correctamente.',
                icon: 'success',
                confirmButtonColor: '#2ecc71'
            });
        }
    }

    // Restablecer asistencias con SweetAlert
    async function resetAttendances() {
        const result = await Swal.fire({
            title: '¿Restablecer asistencias?',
            text: '¿Está seguro que desea restablecer todas las asistencias? Se perderán todos los cambios.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, restablecer',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#3498db'
        });

        if (result.isConfirmed) {
            const month = parseInt(monthSelect.value);
            const year = parseInt(yearSelect.value);
            const daysInMonth = getDaysInMonth(month, year);

            // Eliminar todas las asistencias del mes actual
            students.forEach(student => {
                for (let day = 1; day <= daysInMonth; day++) {
                    const key = getAttendanceKey(student.id, day, month, year);
                    delete attendanceState[key];
                }
            });

            // Regenerar la tabla
            generateAttendanceTable();

            await Swal.fire({
                title: '¡Restablecido!',
                text: 'Todas las asistencias han sido restablecidas.',
                icon: 'success',
                confirmButtonColor: '#2ecc71'
            });
        }
    }

    // Marcar todos como presentes con SweetAlert
    async function markAllPresent() {
        const result = await Swal.fire({
            title: '¿Marcar todos como presentes?',
            text: '¿Está seguro que desea marcar a todos los alumnos como presentes en todos los días?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, marcar todos',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#2ecc71',
            cancelButtonColor: '#e74c3c'
        });

        if (result.isConfirmed) {
            const month = parseInt(monthSelect.value);
            const year = parseInt(yearSelect.value);
            const daysInMonth = getDaysInMonth(month, year);

            students.forEach(student => {
                for (let day = 1; day <= daysInMonth; day++) {
                    saveAttendance(student.id, day, month, year, true);
                }
            });

            // Regenerar la tabla
            generateAttendanceTable();

            await Swal.fire({
                title: '¡Completado!',
                text: 'Todos los alumnos han sido marcados como presentes.',
                icon: 'success',
                confirmButtonColor: '#2ecc71'
            });
        }
    }

    // Marcar todos como ausentes con SweetAlert
    async function markAllAbsent() {
        const result = await Swal.fire({
            title: '¿Marcar todos como ausentes?',
            text: '¿Está seguro que desea marcar a todos los alumnos como ausentes en todos los días?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, marcar todos',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#3498db'
        });

        if (result.isConfirmed) {
            const month = parseInt(monthSelect.value);
            const year = parseInt(yearSelect.value);
            const daysInMonth = getDaysInMonth(month, year);

            students.forEach(student => {
                for (let day = 1; day <= daysInMonth; day++) {
                    saveAttendance(student.id, day, month, year, false);
                }
            });

            // Regenerar la tabla
            generateAttendanceTable();

            await Swal.fire({
                title: '¡Completado!',
                text: 'Todos los alumnos han sido marcados como ausentes.',
                icon: 'success',
                confirmButtonColor: '#2ecc71'
            });
        }
    }

    // Event listeners para los 4 botones con SweetAlert
    saveBtn.addEventListener('click', saveAttendances);
    resetBtn.addEventListener('click', resetAttendances);
    markAllPresentBtn.addEventListener('click', markAllPresent);
    markAllAbsentBtn.addEventListener('click', markAllAbsent);

    // Event listener para mostrar tabla (sin SweetAlert)
    generateBtn.addEventListener('click', generateAttendanceTable);

    // Inicializar la aplicación
    initializeYearSelect();
    generateAttendanceTable();

// --- INICIO: Funcionalidad de Carreras ---

// Función para cargar datos de carrera en el modal de edición
function cargarDatosCarreraEnModal(carrera) {
    $('#edit_id_car').val(carrera.id);
    $('#edit_ncar').val(carrera.nombre_carrera);
    $('#edit_codcar').val(carrera.codigo_carrera);
    $('#edit_duracion').val(carrera.duracion);
    $('#edit_categoria').val(carrera.categoria_id || '');
    $('#edit_modalidad').val(carrera.modalidad_id || '');
}

// Evento para el botón de editar carrera
$(document).on('click', '.edit-car-btn', function() {
    const careerId = $(this).data('id');

    $.ajax({
        url: `${window.APP_CONFIG.baseUrl}carreras/edit/${careerId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response) {
                cargarDatosCarreraEnModal(response);
            } else {
                Swal.fire('Error', 'No se pudieron cargar los datos de la carrera', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Error al cargar los datos de la carrera', 'error');
        }
    });
});

// Evento para el formulario de edición de carrera
$('#editCareerForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const careerId = $('#edit_id_car').val();

    $.ajax({
        url: `${window.APP_CONFIG.baseUrl}carreras/update/${careerId}`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success !== false) {
                Swal.fire('Éxito', 'Carrera actualizada correctamente', 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', 'Error al actualizar la carrera', 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Error al actualizar la carrera';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }
            Swal.fire('Error', errorMessage, 'error');
        }
    });
});

// Evento para el formulario de búsqueda de carrera
$('#searchCareerForm').on('submit', function(e) {
    e.preventDefault();

    const careerId = $('#searchCareerId').val();

    $.ajax({
        url: `${window.APP_CONFIG.baseUrl}carreras/search/${careerId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response) {
                let html = `
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Resultado de Búsqueda</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>ID:</strong> ${response.id}</p>
                                    <p><strong>Nombre:</strong> ${response.nombre_carrera}</p>
                                    <p><strong>Código:</strong> ${response.codigo_carrera}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Categoría:</strong> ${response.nombre_categoria || 'No asignada'}</p>
                                    <p><strong>Modalidad:</strong> ${response.nombre_modalidad || 'No asignada'}</p>
                                    <p><strong>Estado:</strong> Activa</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#getResult').html(html);
            } else {
                $('#getResult').html('<div class="alert alert-warning">No se encontró la carrera con ese ID</div>');
            }
        },
        error: function() {
            $('#getResult').html('<div class="alert alert-danger">Error al buscar la carrera</div>');
        }
    });
});

// Evento para limpiar resultados de búsqueda
$('#clearSearchBtn').on('click', function() {
    $('#searchCareerId').val('');
    $('#getResult').html('');
});

// Generación automática de código de carrera
$('#registerName').on('input', function() {
    const nombreCarrera = $(this).val();
    if (nombreCarrera.length > 0) {
        $.ajax({
            url: `${window.APP_CONFIG.baseUrl}carreras/generar-codigo`,
            type: 'GET',
            data: { nombre_carrera: encodeURIComponent(nombreCarrera) },
            success: function(response) {
                if (response.codigo) {
                    $('#careerCode').val(response.codigo);
                }
            }
        });
    }
});

// --- FIN: Funcionalidad de Carreras ---
}

// Funciones para cargar datos desde los endpoints
function cargarCarrerasEnTabla(selectorTabla) {
  fetch('/appuniversidad/public/api/get_carreras.php')
    .then(r => r.json())
    .then(data => {
      if (!Array.isArray(data)) { console.error('Respuesta inválida carreras', data); return; }
      if ($.fn.DataTable.isDataTable(selectorTabla)) {
        $(selectorTabla).DataTable().clear().rows.add(data).draw();
      } else {
        $(selectorTabla).DataTable({
          data,
          columns: [
            { data: 'id', title: 'ID' },
            { data: 'nombre_carrera', title: 'Nombre' },
            { data: 'codigo_carrera', title: 'Código' }
          ]
        });
      }
    })
    .catch(err => console.error('Error fetch carreras:', err));
}

function poblarSelect(url, selectSelector, valueKey='id', textKey='nombre') {
  fetch(url)
    .then(r => r.json())
    .then(data => {
      if (!Array.isArray(data)) { console.error('Respuesta inválida select', data); return; }
      const sel = document.querySelector(selectSelector);
      if (!sel) return;
      sel.innerHTML = '<option value=\"\">Seleccionar</option>';
      data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item[valueKey];
        opt.text = item[textKey] || item['nombre_categoria'] || item['nombre_modalidad'] || '';
        sel.appendChild(opt);
      });
    })
    .catch(err => console.error('Error fetch select:', err));
}

// Uso
document.addEventListener('DOMContentLoaded', function() {
  cargarCarrerasEnTabla('#tablaCarreras');
  poblarSelect('/appuniversidad/public/api/get_categorias.php', '#selectCategoria', 'id', 'nombre_categoria');
  poblarSelect('/appuniversidad/public/api/get_modalidades.php', '#selectModalidad', 'id', 'nombre_modalidad');
});
