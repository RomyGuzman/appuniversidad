# 📋 Documentación Completa de Vistas - App Universidad

## 🎯 Introducción

Esta documentación proporciona una explicación detallada y didáctica de todas las vistas implementadas en la aplicación **App Universidad**, desarrollada con **CodeIgniter 4**. Las vistas son los componentes de presentación que muestran la interfaz de usuario, manejando la renderización de datos y la interacción con el usuario.

### 🏗️ Arquitectura General
- **Framework**: CodeIgniter 4
- **Motor de Vistas**: Blade-like templating
- **Estructura**: MVC (Modelo-Vista-Controlador)
- **Layout Base**: `templates/layout.php`
- **Espacio de nombres**: `App\Views`
- **Herencia**: Sistema de layouts y secciones

### 🔧 Características Comunes de las Vistas

#### 📦 Estructura Básica
- **Namespace**: `App\Views`
|-------------|-------------|-------|
| `index.php` | Página principal del sitio | 1 |
| `login.php` | Formulario de autenticación | 2 |
| `Registro.php` | Formulario de registro estudiantes | 3 |
| `administrador/` | Panel de administración | 4 |
| `Dashboard_Estudiantes/` | Dashboard estudiantes | 5 |
| `Dashboard_Profesores/` | Dashboard profesores | 6 |
| `templates/` | Layouts y componentes reutilizables | 7 |
| `Vistas_Dinamicas/` | Vistas dinámicas de carreras | 8 |
| `errors/` | Páginas de error | 9 |

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🏠 index.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/index.php`
**Tipo**: Vista principal
**Layout**: `templates/layout.php`

### 🎯 Propósito General
Página principal del sitio web que muestra la oferta académica, información institucional y formulario de contacto.

### 📋 Secciones Principales

#### `oferta_academica` - 📄 Oferta Académica
- **Propósito**: Muestra información general sobre las carreras disponibles
- **Contenido**: Hero section, estadísticas, vida estudiantil
- **Vista**: Incluye `templates/oferta_academica.php`

#### `student-life` - 👨‍🎓 Vida Estudiantil
- **Propósito**: Presenta actividades extracurriculares y servicios
- **Elementos**: Clubs, talleres, deportes, servicios de apoyo
- **Diseño**: Cards con iconos y descripciones

#### `contact` - 📞 Formulario de Contacto
- **Propósito**: Permite envío de consultas desde el frontend
- **Campos**: Nombre, email, asunto, mensaje
- **Funcionalidad**: AJAX con SweetAlert2 y CSRF protection
- **API**: `consultas/enviar`

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔑 login.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/login.php`
**Tipo**: Vista independiente (sin layout)
**Framework**: Bootstrap 5 + FontAwesome

### 🎯 Propósito General
Formulario de autenticación para acceso al sistema con opciones de contacto.

### 📋 Secciones Principales

#### `header` - 🎯 Encabezado
- **Propósito**: Presentación del portal de acceso
- **Elementos**: Título, descripción, gradiente de fondo

#### `main` - 📝 Formulario Login
- **Propósito**: Procesamiento de credenciales
- **Campos**: Email/usuario, contraseña
- **Validación**: HTML5 required
- **Acción**: POST (sin especificar, maneja Auth controller)

#### `contact` - 📞 Sección Contacto
- **Propósito**: Información de contacto alternativa
- **Elementos**: Formulario básico, mapa embebido, redes sociales

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📝 Registro.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/Registro.php`
**Tipo**: Vista independiente
**Layout**: `templates/Navbar`
**Framework**: Bootstrap 5 + SweetAlert2

### 🎯 Propósito General
Formulario de registro para nuevos estudiantes con validación y selección de carrera.

### 📋 Secciones Principales

#### `header` - 🎓 Encabezado Registro
- **Propósito**: Introducción al proceso de registro
- **Elementos**: Título, descripción, navbar

#### `registro-container` - 📋 Formulario Principal
- **Propósito**: Captura datos del estudiante
- **Campos**: DNI, nombre, fecha nacimiento, email, carrera
- **Validación**: HTML5 patterns, required
- **Acción**: `registro` controller

#### `scripts` - ⚙️ JavaScript
- **Funcionalidad**: Carga dinámica de modalidades/categorías
- **AJAX**: Fetch a endpoints del controller
- **SweetAlert**: Confirmación de registro exitoso

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍💼 administrador/administradores.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/administrador/administradores.php`
**Layout**: `templates/NavbarAdmin`
**Framework**: Bootstrap 5 + DataTables

### 🎯 Propósito General
Panel de gestión de administradores con información institucional y estadísticas.

### 📋 Secciones Principales

#### `hero-section` - 🏛️ Información Institucional
- **Propósito**: Presenta datos del instituto
- **Elementos**: Estadísticas, visión, valores, funciones del admin

#### `funciones-crud` - 🔧 Operaciones CRUD
- **Propósito**: Muestra operaciones disponibles
- **Cards**: Crear, Leer, Actualizar, Eliminar con iconos

#### `estadisticas` - 📊 Estadísticas Detalladas
- **Propósito**: Métricas institucionales
- **Datos**: Estudiantes, profesores, carreras, materias

#### `llamado-accion` - 🚀 Motivación
- **Propósito**: Inspira al administrador
- **Elementos**: Mensajes motivacionales, alertas

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🎓 administrador/estudiantes.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/administrador/estudiantes.php`
**Layout**: `templates/NavbarAdmin`
**Framework**: Bootstrap 5 + DataTables + SweetAlert2

### 🎯 Propósito General
Gestión completa de estudiantes con formularios CRUD y listados.

### 📋 Secciones Principales

#### `registrar-estudiante` - ➕ Formulario Registro
- **Propósito**: Crear nuevos estudiantes
- **Campos**: Nombre, DNI, edad, email, fecha nacimiento, carrera
- **Validación**: Server-side con mensajes de error

#### `buscar-id` - 🔍 Búsqueda por ID
- **Propósito**: Consulta específica de estudiante
- **Funcionalidad**: AJAX para mostrar detalles

#### `buscar-carrera` - 🎓 Búsqueda por Carrera
- **Propósito**: Filtrar estudiantes por carrera
- **Resultados**: Cards dinámicos con información

#### `listado-estudiantes` - 📋 Tabla General
- **Propósito**: Vista completa de todos los estudiantes
- **Tabla**: DataTable con acciones (editar, eliminar)
- **Modal**: Edición inline con formulario

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📊 Dashboard_Estudiantes/dashboard_estudiante.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/Dashboard_Estudiantes/dashboard_estudiante.php`
**Layout**: `Dashboard_Estudiantes/layout_estudiante`
**Framework**: Bootstrap 5 + SweetAlert2

### 🎯 Propósito General
Dashboard personalizado para estudiantes con materias, notas y asistencia.

### 📋 Secciones Principales

#### `perfil` - 👤 Información Personal
- **Propósito**: Datos del estudiante
- **Elementos**: Nombre, DNI, carrera

#### `estadisticas` - 📈 Estadísticas Académicas
- **Propósito**: Rendimiento general
- **Métricas**: Promedio general, materias aprobadas

#### `materias-inscritas` - 📚 Materias
- **Propósito**: Gestión de asignaturas inscritas
- **Acordeón**: Notas, asistencia, materiales por materia
- **Tabs**: Separación por tipo de información

#### `modal-consulta` - 💬 Contacto Admin
- **Propósito**: Envío de consultas
- **Formulario**: Email, asunto, mensaje
- **AJAX**: Envío asíncrono con validación

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🏫 Dashboard_Profesores/dashboard_profesor.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/Dashboard_Profesores/dashboard_profesor.php`
**Layout**: `Dashboard_Profesores/layout_profesor`
**Framework**: Bootstrap 5 + SweetAlert2

### 🎯 Propósito General
Dashboard para profesores con gestión de materias y estudiantes.

### 📋 Secciones Principales

#### `perfil` - 👤 Información Personal
- **Propósito**: Datos del profesor
- **Elementos**: Nombre, ID, legajo, estadísticas

#### `materias` - 📚 Materias Asignadas
- **Propósito**: Gestión académica
- **Acordeón**: Materias con tabla de asistencia
- **Vista**: `Dashboard_Profesores/_asistencia_table`

#### `modal-consulta` - 💬 Contacto Admin
- **Propósito**: Comunicación con administración
- **Formulario**: Email, asunto, mensaje
- **AJAX**: Procesamiento asíncrono

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🎨 templates/layout.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/templates/layout.php`
**Tipo**: Layout base
**Includes**: head, navbar, header, footer

### 🎯 Propósito General
Layout principal que estructura todas las páginas del sitio.

### 📋 Componentes Principales

#### `head` - 🎯 Cabecera HTML
- **Include**: `templates/head.php`
- **Elementos**: Meta tags, CSS, scripts

#### `navbar` - 🧭 Navegación
- **Include**: `templates/Navbar.php`
- **Elementos**: Menú principal, responsive

#### `header` - 🎨 Encabezado
- **Condicional**: Minimal o completo según configuración
- **Includes**: `header_content.php` o `header_content_minimal.php`

#### `main` - 📄 Contenido Principal
- **Sección**: `content` o variable `$page_content`
- **Render**: `renderSection()` o echo directo

#### `footer` - 🦶 Pie de Página
- **Include**: `templates/footer.php`
- **Elementos**: Información institucional, enlaces

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🤖 Vistas_Dinamicas/ciencia_datos.php
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Views/Vistas_Dinamicas/ciencia_datos.php`
**Tipo**: Vista dinámica de carrera
**Framework**: HTML5 + CSS personalizado

### 🎯 Propósito General
Presentación detallada de la Tecnicatura en Ciencia de Datos e IA.

### 📋 Secciones Principales

#### `hero-career` - 🚀 Hero Section
- **Propósito**: Introducción impactante
- **Elementos**: Título, descripción, badges, CTAs

#### `descripcion` - 📖 Información General
- **Propósito**: Detalles de la carrera
- **Contenido**: ¿Por qué estudiar?, qué aprender
- **Imágenes**: Dinámicas desde variables PHP

#### `estadisticas` - 📊 Métricas
- **Propósito**: Datos relevantes
- **Counters**: Inserción laboral, crecimiento, salario, empresas

#### `plan-estudios` - 📚 Currícula
- **Propósito**: Estructura académica
- **Años**: Primer, segundo, tercer año
- **Materias**: Listadas con iconos

#### `perfil-egresado` - 🎓 Salidas Profesionales
- **Propósito**: Competencias y empleos
- **Áreas**: Empresas, industrias, sectores

#### `testimonios` - 💬 Opiniones
- **Propósito**: Experiencias de estudiantes
- **Cards**: Testimonios con autores

#### `cta-inscripcion` - 📝 Llamado a Acción
- **Propósito**: Conversión a registro
- **Elementos**: Fechas, WhatsApp, formularios

---

## 🔧 Características Comunes

### 🛡️ Manejo de Seguridad
- **CSRF Protection**: Tokens en formularios
- **XSS Prevention**: `htmlspecialchars()` en outputs
- **Validación**: HTML5 + server-side

### 🔄 Interactividad
- **AJAX**: Envío asíncrono de formularios
- **SweetAlert2**: Notificaciones modernas
- **DataTables**: Tablas interactivas
- **Bootstrap Modals**: Diálogos dinámicos

### 📱 Responsive Design
- **Bootstrap 5**: Grid system adaptable
- **Mobile-first**: Optimizado para móviles
- **Flexbox**: Layouts flexibles

### 🎨 Estilos y UX
- **FontAwesome**: Iconografía consistente
- **Gradientes**: Fondos atractivos
- **Animaciones**: AOS (Animate On Scroll)
- **Accesibilidad**: Labels, ARIA attributes

---

## 📈 Conclusión

Esta documentación proporciona una visión completa de la arquitectura de vistas de **App Universidad**. Cada vista sigue principios de diseño responsivo, accesibilidad y usabilidad, con una estructura modular que facilita el mantenimiento y escalabilidad.

**Recomendaciones**:
- Mantener consistencia en el uso de Bootstrap
- Implementar componentes reutilizables
- Optimizar imágenes y assets
- Documentar variables PHP utilizadas
- Considerar implementación de Vue.js/React para mayor interactividad
