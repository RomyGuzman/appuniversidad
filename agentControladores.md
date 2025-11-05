# 📋 Documentación Completa de Controladores - App Universidad

## 🎯 Introducción

Esta documentación proporciona una explicación detallada y didáctica de todos los controladores implementados en la aplicación **App Universidad**, desarrollada con **CodeIgniter 4**. Los controladores actúan como el "cerebro" de la aplicación, manejando la lógica de negocio, procesando las peticiones del usuario y coordinando la interacción entre modelos y vistas.

### 🏗️ Arquitectura General
- **Framework**: CodeIgniter 4
- **Patrón**: MVC (Modelo-Vista-Controlador)
- **Controlador Base**: `BaseController`
- **Espacio de nombres**: `App\Controllers`
- **Herencia**: Todos los controladores heredan de `BaseController`

### 🔧 Características Comunes de los Controladores

#### 📦 Estructura Básica
- **Namespace**: `App\Controllers`
|-------------|-------------|-------|
| `Administradores` | Gestión completa de administradores | 1 |
| `AjaxController` | Manejo de peticiones AJAX dinámicas | 2 |
| `alertas` | Sistema de alertas (vacío) | 3 |
| `Auth` | Autenticación y autorización de usuarios | 4 |
| `BaseController` | Controlador base con funcionalidades comunes | 5 |
| `Carreras` | Vista específica de Ciencia de Datos | 6 |
| `Categorias` | Gestión de categorías de carreras | 7 |
| `Consultas` | Envío de consultas desde el frontend | 8 |
| `ConsultasAdmin` | Gestión de consultas en el panel admin | 9 |
| `Estudiantes` | Gestión completa de estudiantes | 10 |
| `Home` | Página principal del sitio | 11 |
| `LoginController` | Controlador de login alternativo | 12 |
| `Materias` | Gestión completa de materias | 13 |
| `Modalidades` | Gestión de modalidades de estudio | 14 |
| `Profesores` | Gestión completa de profesores | 15 |
| `RegistrarCarrera` | CRUD completo de carreras | 16 |
| `RegistroController` | Registro de nuevos estudiantes | 17 |
| `Rol` | Gestión de roles de usuario | 18 |
| `RoutesAdmin` | Definición de rutas administrativas | 19 |
| `Usuarios` | Gestión completa de usuarios | 20 |

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍💼 Administradores
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Administradores.php`
**Herencia**: Extiende `BaseController`
**Modelos utilizados**: `AdministradorModel`

### 🎯 Propósito General
Gestiona todas las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) para los administradores del sistema.

### 📋 Métodos Principales

#### `index()` - 📋 Lista de Administradores
- **Propósito**: Muestra la página principal de gestión de administradores
- **Funcionalidad**:
  - Instancia `AdministradorModel`
  - Obtiene lista completa con `getAdministradores()`
  - Maneja errores de conexión a BD
  - Renderiza vista `administrador/administradores`
- **Vista**: `administrador/administradores.php`

#### `registrar()` - ➕ Crear Administrador
- **Propósito**: Procesa formulario de creación de administrador
- **Validación**: Maneja errores del modelo
- **Redirección**: Vuelve al formulario con errores o éxito
- **Campos**: `dni`, `nadmin` (nota: código parece incompleto)

#### `update($id)` - ✏️ Actualizar Administrador
- **Propósito**: Procesa formulario de edición
- **Validación**: Usa reglas del modelo
- **Redirección**: Con mensaje de éxito/error

#### `delete($id)` - 🗑️ Eliminar Administrador
- **Propósito**: Elimina administrador por ID
- **Validación**: Verifica eliminación exitosa
- **Redirección**: Con mensaje correspondiente

#### `search($id)` - 🔍 Buscar por ID
- **Propósito**: Búsqueda AJAX de administrador
- **Respuesta**: JSON con datos o error 404
- **Uso**: Para modales de edición

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔄 AjaxController
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/AjaxController.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `CarreraModel`

### 🎯 Propósito General
Maneja peticiones AJAX para cargar contenido dinámico en el frontend, principalmente vistas de oferta académica.

### 📋 Métodos Principales

#### `oferta_academica_default()` - 📄 Vista por Defecto
- **Propósito**: Carga vista de oferta académica por defecto
- **Vista**: `templates/oferta_academica_default`

#### `ciencia_datos()` - 🤖 Ciencia de Datos
- **Propósito**: Vista detallada de Tecnicatura en Ciencia de Datos
- **Datos**: Información específica de la carrera
- **Vista**: `Vistas_Dinamicas/ciencia_datos`

#### `profesorado_matematica()` - 🔢 Profesorado Matemática
- **Vista**: `Vistas_Dinamicas/profesorado_matematica`

#### `profesorado_ingles()` - 🇬🇧 Profesorado Inglés
- **Vista**: `Vistas_Dinamicas/profesorado_ingles`

#### `seguridad_higiene()` - 🛡️ Seguridad e Higiene
- **Vista**: `Vistas_Dinamicas/seguridad_higiene`

#### `enfermeria()` - 🏥 Enfermería
- **Vista**: `Vistas_Dinamicas/enfermeria`

#### `educacion_inicial()` - 🧸 Educación Inicial
- **Vista**: `Vistas_Dinamicas/educacion_inicial`

#### `registro()` - 📝 Formulario de Registro
- **Propósito**: Carga formulario de registro con dropdowns
- **Datos**: Carreras ordenadas alfabéticamente
- **Vista**: `registro`

#### `test()` - 🧪 Prueba AJAX
- **Propósito**: Vista de prueba simple
- **Retorno**: HTML básico de confirmación

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🚨 alertas
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/alertas.php`
**Estado**: Archivo vacío o sin contenido relevante

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔐 Auth
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Auth.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `UsuarioModel`, `EstudianteModel`, `ProfesorModel`, `RolModel`

### 🎯 Propósito General
Maneja todo el sistema de autenticación y autorización de usuarios, incluyendo login, logout y redirección según roles.

### 📋 Métodos Principales

#### `login()` - 🔑 Mostrar Formulario Login
- **Propósito**: Muestra formulario de inicio de sesión
- **Validación**: Redirige si ya está logueado
- **Vista**: `login`

#### `attemptLogin()` - 🚪 Procesar Login
- **Validación**: Campos requeridos (identifier, password)
- **Autenticación**: Busca usuario por nombre, compara MD5
- **Sesión**: Establece datos de usuario y rol
- **Redirección**: Según rol (admin/profesor/estudiante)

#### `logout()` - 🚪 Cerrar Sesión
- **Funcionalidad**: Destruye sesión
- **Redirección**: A página principal

#### `setUserSession($usuario)` - 💾 Configurar Sesión
- **Propósito**: Establece variables de sesión
- **Datos**: ID, usuario, rol, nombre_rol, isLoggedIn

#### `getDashboardRedirect($rol_id)` - 🧭 Redirección por Rol
- **Lógica**:
  - Rol 1/4 (Admin/Superadmin): `administrador/usuarios`
  - Rol 2 (Profesor): `profesores/dashboard`
  - Rol 3 (Estudiante): `estudiantes/dashboard`

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🏗️ BaseController
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/BaseController.php`  
**Herencia**: Extiende `Controller` de CodeIgniter

### 🎯 Propósito General
Controlador base que proporciona funcionalidades comunes a todos los controladores de la aplicación.

### 📋 Características Principales

#### Propiedades
- `$request`: Instancia de la petición HTTP
- `$helpers`: Helpers cargados automáticamente (`url`)
- `$session`: Servicio de sesión

#### `initController()` - 🔧 Inicialización
- **Propósito**: Ejecutado automáticamente al instanciar
- **Funcionalidad**:
  - Llama al padre
  - Inicializa servicio de sesión
  - Carga helpers

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🎓 Carreras
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Carreras.php`  
**Herencia**: Extiende `Controller`

### 🎯 Propósito General
Controlador simple para mostrar vista específica de Ciencia de Datos.

### 📋 Métodos Principales

#### `ciencia_datos()` - 🤖 Vista Carrera
- **Vista**: `vistas_dinamicas/ciencia_datos`

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📂 Categorias
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Categorias.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `CategoriaModel`

### 🎯 Propósito General
Gestiona operaciones CRUD para las categorías de carreras.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Categorías
- **Funcionalidad**: Muestra todas las categorías
- **Vista**: `administrador/categorias`

#### `registrar()` - ➕ Crear Categoría
- **Campos**: `nombre_categoria`, `codigo_categoria`
- **Validación**: Reglas del modelo

#### `update($id)` - ✏️ Actualizar Categoría
- **Validación**: Reglas del modelo

#### `delete($id)` - 🗑️ Eliminar Categoría
- **Validación**: Verifica eliminación

#### `search($id)` - 🔍 Buscar por ID
- **Respuesta**: JSON para AJAX

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 💬 Consultas
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Consultas.php`  
**Herencia**: Extiende `Controller`  
**Modelos utilizados**: `ConsultaAdminModel`

### 🎯 Propósito General
Maneja el envío de consultas desde el frontend público.

### 📋 Métodos Principales

#### `enviar()` - 📤 Enviar Consulta
- **Validación**: Email, asunto, mensaje, tipo_usuario
- **Campos**: email_usuario, asunto, mensaje, estado, usuario_id, rol_id
- **Respuesta**: AJAX o redirección
- **Lógica**: Asigna IDs según tipo de usuario

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📬 ConsultasAdmin
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/ConsultasAdmin.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `ConsultaAdminModel`

### 🎯 Propósito General
Gestiona las consultas en el panel de administración.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Consultas
- **Funcionalidad**: Muestra consultas paginadas (15 por página)
- **Vista**: `administrador/alertas`

#### `getUnreadCount()` - 🔢 Contador No Leídas
- **Propósito**: AJAX para contador de consultas pendientes
- **Respuesta**: JSON con `unread_count`

#### `markAsRead($id)` - ✅ Marcar como Leída
- **Propósito**: Cambia estado a 'respondida'
- **Respuesta**: JSON con éxito/error

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🎓 Estudiantes
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Estudiantes.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `EstudianteModel`, `CarreraModel`, `MateriaModel`

### 🎯 Propósito General
Gestiona estudiantes y su dashboard personalizado.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Estudiantes
- **Funcionalidad**: Muestra estudiantes con nombre de carrera
- **Vista**: `administrador/estudiantes`

#### `dashboard()` - 📊 Dashboard Estudiante
- **Verificación**: Sesión activa
- **Datos**: Estudiante, notas, materias inscritas, estadísticas
- **Vista**: `Dashboard_Estudiantes/dashboard_estudiante`

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🏠 Home
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Home.php`  
**Herencia**: Extiende `BaseController`

### 🎯 Propósito General
Maneja la página principal del sitio web.

### 📋 Métodos Principales

#### `index()` - 🏠 Página Principal
- **Funcionalidad**: Carga vista principal con layout
- **Vistas**: `index` dentro de `templates/layout`

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔑 LoginController
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/LoginController.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `UsuarioModel`

### 🎯 Propósito General
Controlador alternativo para autenticación de usuarios.

### 📋 Métodos Principales

#### `index()` - 📝 Formulario Login
- **Vista**: `login`

#### `autenticar()` - 🔐 Procesar Autenticación
- **Validación**: Campos requeridos
- **Autenticación**: MD5 para contraseña
- **Redirección**: Según rol

#### `logout()` - 🚪 Cerrar Sesión
- **Funcionalidad**: Destruye sesión

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📚 Materias
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Materias.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `MateriaModel`, `CarreraModel`

### 🎯 Propósito General
Gestiona operaciones CRUD para las materias académicas.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Materias
- **Funcionalidad**: Paginación, filtrado por carrera
- **Vista**: `administrador/materias`

#### `registrar()` - ➕ Crear Materia
- **Generación**: Código automático único
- **Campos**: nombre, código, carrera_id

#### `generarCodigoMateria()` - 🔢 Generar Código
- **Lógica**: Acrónimo + número secuencial

#### `searchCarrera()` - 🔍 Buscar Carreras
- **Respuesta**: JSON con carreras filtradas

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📋 Modalidades
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Modalidades.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `ModalidadModel`, `CarreraModel`

### 🎯 Propósito General
Gestiona modalidades de estudio asociadas a carreras.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Modalidades
- **Vista**: `administrador/modalidades`

#### `registrar()` - ➕ Crear Modalidad
- **Campos**: codigo_modalidad, nombre_modalidad, carrera_id

#### `update($id)` - ✏️ Actualizar Modalidad

#### `delete($id)` - 🗑️ Eliminar Modalidad

#### `search($id)` - 🔍 Buscar por ID

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🏫 Profesores
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Profesores.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `ProfesorModel`, `CarreraModel`, `NotaModel`, `AsistenciaModel`

### 🎯 Propósito General
Gestiona profesores y su dashboard con funcionalidades avanzadas.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Profesores
- **Vista**: `administrador/profesores`

#### `dashboard()` - 📊 Dashboard Profesor
- **Datos**: Materias dictadas, estudiantes, notas, asistencias
- **Vista**: `Dashboard_Profesores/dashboard_profesor`

#### `guardarNotas()` - 💾 Guardar Notas
- **Funcionalidad**: Actualiza/inserta calificaciones

#### `guardarAsistencia()` - 📅 Guardar Asistencia
- **Funcionalidad**: AJAX para marcar asistencia

#### `getTablaAsistenciaMensual()` - 📊 Tabla Asistencia
- **Generación**: HTML completo de tabla mensual

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🎯 RegistrarCarrera
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/RegistrarCarrera.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `CarreraModel`, `CategoriaModel`, `ModalidadModel`, `UsuarioModel`

### 🎯 Propósito General
Maneja todas las operaciones CRUD para las carreras académicas.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Carreras
- **Datos**: Carreras completas con nombres relacionados
- **Vista**: `administrador/registrarCarrera`

#### `registrar()` - ➕ Crear Carrera
- **Generación**: Código automático único
- **Campos**: nombre, código, categoria_id, modalidad_id

#### `generarCodigoCarrera()` - 🔢 Generar Código
- **Lógica**: Acrónimo de palabras + secuencial

#### `generarCodigoAjax()` - 🔄 Código en Tiempo Real
- **Propósito**: AJAX para preview de código

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 📝 RegistroController
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/RegistroController.php`  
**Herencia**: Extiende `Controller`  
**Modelos utilizados**: `RegistroEstudianteModel`, `CarreraModel`, `UsuarioModel`

### 🎯 Propósito General
Maneja el registro de nuevos estudiantes desde el frontend público.

### 📋 Métodos Principales

#### `index()` - 📝 Formulario Registro
- **Datos**: Carreras ordenadas
- **Vista**: `registro`

#### `registrar()` - ✅ Procesar Registro
- **Validación**: DNI, nombre, fecha, email, carrera
- **Creación**: Estudiante + Usuario automático
- **Contraseña**: MD5 del DNI

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👥 Rol
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Rol.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `RolModel`

### 🎯 Propósito General
Gestiona los roles de usuario del sistema.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Roles
- **Vista**: `administrador/rol`

#### `registrar()` - ➕ Crear Rol
- **Campo**: nombre_rol

#### `update($id)` - ✏️ Actualizar Rol

#### `delete($id)` - 🗑️ Eliminar Rol

#### `search($id)` - 🔍 Buscar por ID

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🛣️ RoutesAdmin
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/RoutesAdmin.php`  
**Tipo**: Archivo de configuración de rutas

### 🎯 Propósito General
Define todas las rutas del panel de administración con el prefijo `/admin`.

### 📋 Grupos de Rutas

#### Estudiantes
- CRUD completo: index, registrar, edit, update, delete, search
- Búsqueda por carrera

#### Carreras
- CRUD completo
- Generación de código AJAX

#### Categorías
- CRUD completo

#### Alertas
- Lista, contador no leídas, marcar como leída

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👤 Usuarios
*********************************************************************************************************************************
*********************************************************************************************************************************

**Ubicación**: `app/Controllers/Usuarios.php`  
**Herencia**: Extiende `BaseController`  
**Modelos utilizados**: `UsuarioModel`, `RolModel`

### 🎯 Propósito General
Gestiona usuarios del sistema con roles y permisos.

### 📋 Métodos Principales

#### `index()` - 📋 Lista Usuarios
- **Vista**: `administrador/usuarios`

#### `registrar()` - ➕ Crear Usuario
- **Validación**: Usuario único
- **Encriptación**: MD5 para contraseña

#### `update($id)` - ✏️ Actualizar Usuario
- **Validación**: Usuario único (si cambió)
- **Contraseña**: Opcional, MD5 si se actualiza

#### `delete($id)` - 🗑️ Eliminar Usuario

#### `search($id)` - 🔍 Buscar por ID

---

## 🔧 Características Comunes

### 🛡️ Manejo de Errores
- Try-catch para errores de conexión a BD
- Validación de formularios
- Mensajes flash de éxito/error

### 🔄 Peticiones AJAX
- Respuestas JSON
- Verificación `isAJAX()`
- Manejo de errores 403/404

### 🔐 Autenticación y Autorización
- Verificación de sesión
- Redirección por roles
- Protección de rutas

### 📊 Paginación
- Integración con Pager de CodeIgniter
- Configurable por controlador

### 🎨 Vistas
- Separación clara entre admin y público
- Dashboards específicos por rol
- Templates reutilizables

---

## 📈 Conclusión

Esta documentación proporciona una visión completa de la arquitectura de controladores de **App Universidad**. Cada controlador sigue el patrón MVC, con responsabilidades claras y funcionalidades bien definidas. El sistema está diseñado para ser escalable, mantenible y seguro.

**Recomendaciones**:
- Revisar controladores vacíos como `alertas.php`
- Implementar validaciones consistentes
- Documentar métodos privados importantes
- Considerar refactorización para reducir duplicación de código
