# 📋 Documentación Completa de app.js - App Universidad

## 🎯 Introducción

Esta documentación proporciona una explicación detallada y didáctica del archivo **app.js**, el archivo JavaScript principal de la aplicación **App Universidad**. Este archivo maneja toda la interactividad del frontend, incluyendo AJAX, DataTables, SweetAlert2, y funcionalidades específicas de cada módulo.

### 🏗️ Arquitectura General
- **Framework**: jQuery + Vanilla JavaScript
- **Ubicación**: `public/app.js`
- **Carga**: Automática en todas las páginas
- **Dependencias**: jQuery, DataTables, SweetAlert2
- **Alcance**: Frontend completo

### 🔧 Características Principales

#### 📦 Estructura del Archivo
- **Inicialización**: `$(document).ready()`
- **Variables Globales**: `BASE_URL`, `window.APP_CONFIG`
- **Módulos**: Lógica separada por roles (estudiantes, profesores, admin)
- **Funciones Reutilizables**: AJAX, confirmaciones, validaciones
- **Eventos**: Delegación para elementos dinámicos

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🚀 INICIALIZACIÓN Y CONFIGURACIÓN
*********************************************************************************************************************************
*********************************************************************************************************************************

### Document Ready
```javascript
$(document).ready(function () {
    // Configuración inicial
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    if (!window.location.hash) {
        window.scrollTo(0, 0);
    }

    console.log('Script cargado');
    const BASE_URL = window.APP_CONFIG.baseUrl;
```
**Propósito**: Inicialización del script al cargar la página
**Funcionalidad**:
- Deshabilita restauración de scroll del navegador
- Scroll automático al inicio (sin hash en URL)
- Configuración de variables globales

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🎓 LÓGICA PARA ESTUDIANTES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Edición de Estudiantes
```javascript
$('#studentsTable').on('click', '.edit-btn', function () {
    const studentId = $(this).data('id');
    $.ajax({
        url: `${BASE_URL}estudiantes/edit/${studentId}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            // Rellenar modal con datos
            $('#edit_id').val(response.id);
            $('#edit_nest').val(response.nombre_estudiante);
            // ... más campos
        }
    });
});
```
**Propósito**: Cargar datos de estudiante en modal de edición
**Elementos**: Tabla estudiantes, botones editar
**AJAX**: GET a `estudiantes/edit/{id}`

### Eliminación con Confirmación
```javascript
$('body').on('submit', '.delete-form', function (e) {
    e.preventDefault();
    showDeleteConfirmation(this);
});
```
**Propósito**: Confirmación antes de eliminar
**Funcionalidad**: Previene envío, muestra SweetAlert

### Búsqueda por ID
```javascript
$('#searchStudentForm').on('submit', function(e) {
    e.preventDefault();
    const studentId = $('#searchStudentId').val();
    // AJAX a estudiantes/search/{id}
});
```
**Propósito**: Buscar estudiante específico
**Resultado**: Mostrar detalles en contenedor

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🏫 LÓGICA PARA PROFESORES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Edición de Profesores
```javascript
$('#profsTable').on('click', '.edit-btn', function () {
    const profId = $(this).data('id');
    // AJAX similar a estudiantes
});
```
**Propósito**: Gestión de datos de profesores
**Campos**: ID, nombre, legajo

### Búsqueda por Legajo
```javascript
$('#searchProfByLegajoForm').on('submit', function(e) {
    e.preventDefault();
    const profLegajo = $('#searchProfLegajo').val();
    // AJAX a profesores/searchByLegajo/{legajo}
});
```
**Propósito**: Búsqueda alternativa por legajo
**Parámetro**: `:any` (admite texto)

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍💼 LÓGICA PARA USUARIOS
*********************************************************************************************************************************
*********************************************************************************************************************************

### Gestión de Usuarios del Sistema
```javascript
$('#usuariosTable').on('click', '.edit-btn', function () {
    const usuarioId = $(this).data('id');
    // AJAX para cargar datos de usuario
    // Campos: usuario, rol_id, activo
});
```
**Propósito**: Administración de usuarios del sistema
**Campos**: Usuario, rol, estado activo

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🎓 LÓGICA PARA CARRERAS
*********************************************************************************************************************************
*********************************************************************************************************************************

### Generación Automática de Códigos
```javascript
$('#registerName').on('input', function() {
    const nombreCarrera = $(this).val().trim();
    if (nombreCarrera.length < 3) return;

    debounceTimer = setTimeout(() => {
        const nombreCodificado = encodeURIComponent(nombreCarrera);
        $.ajax({
            url: `${BASE_URL}carreras/generar-codigo/${nombreCodificado}`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.codigo) {
                    $('#careerCode').val(response.codigo);
                }
            }
        });
    }, 500);
});
```
**Propósito**: Generar códigos automáticamente
**Técnica**: Debounce (500ms) para evitar spam
**Endpoint**: `carreras/generar-codigo/{nombre}`

### Edición de Carreras
```javascript
$('#careersTable').on('click', '.edit-car-btn', function() {
    const careerId = $(this).data('id');
    // AJAX para cargar datos de carrera
    // Campos: nombre, código, duración, categoría, modalidad
});
```
**Propósito**: Gestión completa de carreras académicas

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📊 FUNCIONALIDAD SPA (Single Page Application)
*********************************************************************************************************************************
*********************************************************************************************************************************

### Carga Dinámica de Carreras
```javascript
function cargarContenidoCarrera(url, containerSelector = '#careers') {
    const contentContainer = $(containerSelector);
    contentContainer.addClass('loading-content');
    contentContainer.fadeOut(200, function() {
        $.ajax({
            url: `${BASE_URL}${url}`,
            type: 'GET',
            success: function(response) {
                // Añadir botón "Volver" si no es vista por defecto
                if (url !== 'ajax/oferta_academica_default' && url !== 'ajax/registro') {
                    const volverBtnHtml = `...`;
                    finalHtml += volverBtnHtml;
                }
                contentContainer.html(finalHtml).fadeIn(300);
                // Re-inicializar AOS
                if (typeof AOS !== 'undefined') {
                    AOS.init({ once: true });
                }
                // Scroll suave
                $('html, body').animate({
                    scrollTop: contentContainer.offset().top - 80
                }, 800);
            }
        });
    });
}
```
**Propósito**: Carga de vistas de carreras sin recargar página
**Características**:
- Feedback visual (loading)
- Animaciones de entrada/salida
- Re-inicialización de librerías
- Scroll automático suave

### Manejador Unificado de Navegación
```javascript
$('body').on('click', 'a[id$="-link"], a[id^="ver-detalle-"], .btn-inscribir, #volver-oferta-default', function(e) {
    e.preventDefault();
    let url;
    if ($(this).hasClass('btn-inscribir')) {
        url = 'ajax/registro';
    } else if (this.id === 'volver-oferta-default') {
        url = 'ajax/oferta_academica_default';
    } else {
        const slugBase = this.id.replace('-link', '').replace('ver-detalle-', '');
        const slugFinal = slugBase.replace(/-/g, '_');
        url = `ajax/${slugFinal}`;
    }
    cargarContenidoCarrera(url);
    $('.navbar-collapse').collapse('hide');
});
```
**Propósito**: Manejar todos los clics de navegación SPA
**Selectores**: Enlaces de navbar, botones detalle, botones inscribir
**Lógica**: Conversión de slugs (guiones a guiones bajos)

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔔 SISTEMA DE ALERTAS DEL ADMINISTRADOR
*********************************************************************************************************************************
*********************************************************************************************************************************

### Contador de Alertas
```javascript
function actualizarContadorAlertas() {
    if ($('#alerta-contador').length) {
        $.ajax({
            url: `${BASE_URL}administrador/alertas/count`,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                const count = response.unread_count;
                if (count > 0) {
                    $('#alerta-contador').text(count).removeClass('d-none');
                } else {
                    $('#alerta-contador').addClass('d-none');
                }
            }
        });
    }
}
```
**Propósito**: Mostrar número de consultas no leídas
**Actualización**: Cada 60 segundos

### Marcar como Resuelta
```javascript
$('body').on('click', '.mark-as-read-btn', function() {
    const consultaId = $(this).data('id');
    Swal.fire({
        title: '¿Marcar como resuelta?',
        // ... configuración SweetAlert
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfName = $('input[name=csrf_test_name]').attr('name');
            const csrfHash = $('input[name=csrf_test_name]').val();
            $.ajax({
                url: `${BASE_URL}administrador/alertas/mark-as-read/${consultaId}`,
                type: 'POST',
                data: { [csrfName]: csrfHash },
                success: function(response) {
                    // Actualizar UI y contador
                }
            });
        }
    });
});
```
**Propósito**: Resolver consultas del sistema
**Seguridad**: Token CSRF incluido

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📅 SISTEMA DE ASISTENCIA
*********************************************************************************************************************************
*********************************************************************************************************************************

### Inicialización de Tablas de Asistencia
```javascript
const attendanceContainers = document.querySelectorAll('.attendance-container');
attendanceContainers.forEach(container => {
    const materiaId = container.dataset.materiaId;
    if (materiaId) {
        initializeAttendanceTable(parseInt(materiaId, 10));
    }
});
```
**Propósito**: Inicializar múltiples tablas de asistencia
**Alcance**: Una por materia

### Funciones de Asistencia
- **Generar tabla**: Crear tabla mensual con checkboxes
- **Guardar estado**: Persistencia de asistencias
- **Calcular porcentajes**: Estadísticas en tiempo real
- **Acciones masivas**: Marcar todos presentes/ausentes
- **SweetAlert**: Confirmaciones para todas las acciones

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔧 FUNCIONES REUTILIZABLES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Confirmación de Eliminación
```javascript
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
            form.submit();
        }
    });
}
```
**Propósito**: Confirmación genérica para eliminaciones
**Parámetros**: Formulario a enviar

### Mensajes Flash
```javascript
if (window.APP_CONFIG.flash.success) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        html: window.APP_CONFIG.flash.success,
        showConfirmButton: false,
        timer: 2000
    });
}
```
**Propósito**: Mostrar mensajes del backend
**Tipos**: Success, error

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔐 SEGURIDAD Y CONFIRMACIONES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Logout con Confirmación
```javascript
$('body').on('click', '.logout-btn', function(e) {
    e.preventDefault();
    const href = $(this).attr('href');
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Estás a punto de cerrar tu sesión.",
        // ... configuración
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
});
```
**Propósito**: Confirmación antes de cerrar sesión

### Protección CSRF
```javascript
const csrfName = $('input[name=csrf_test_name]').attr('name');
const csrfHash = $('input[name=csrf_test_name]').val();
```
**Propósito**: Obtener tokens CSRF dinámicamente
**Uso**: En todas las peticiones POST

---

## 🎨 Características de UX/UI

### 📱 Interactividad
- **DataTables**: Tablas interactivas y filtrables
- **SweetAlert2**: Modales modernos y atractivos
- **AOS**: Animaciones de scroll
- **Loading states**: Feedback visual durante AJAX

### ⚡ Optimizaciones
- **Debounce**: Evita llamadas excesivas en inputs
- **Delegación**: Eventos en elementos dinámicos
- **Cache**: Variables globales para URLs
- **Scroll suave**: Navegación fluida

### 🔄 Estados y Feedback
- **Loading**: Indicadores durante procesos
- **Success/Error**: Mensajes claros
- **Confirmaciones**: Prevención de acciones accidentales
- **Actualización en tiempo real**: Contadores, porcentajes

---

## 📈 Conclusión

El archivo **app.js** es el corazón de la interactividad de **App Universidad**, manejando desde operaciones CRUD básicas hasta funcionalidades avanzadas como SPA y sistemas de asistencia. Su estructura modular y el uso de mejores prácticas lo hacen mantenible y escalable.

**Recomendaciones**:
- Considerar migración a módulos ES6
- Implementar error boundaries
- Añadir logging para debugging
- Optimizar para Core Web Vitals
- Documentar nuevas funciones agregadas
