# 📋 Documentación Completa de Rutas - App Universidad

## 🎯 Introducción

Esta documentación proporciona una explicación detallada y didáctica de todas las rutas implementadas en la aplicación **App Universidad**, desarrollada con **CodeIgniter 4**. Las rutas son los puntos de entrada que conectan las URLs con los controladores, manejando la navegación y el flujo de la aplicación.

### 🏗️ Arquitectura General
- **Framework**: CodeIgniter 4
- **Motor de Rutas**: RouteCollection
- **Estructura**: MVC (Modelo-Vista-Controlador)
- **Métodos HTTP**: GET, POST
- **Grupos**: Organización por módulos/roles
- **Filtros**: Seguridad y autenticación

### 🔧 Características Comunes de las Rutas

#### 📦 Estructura Básica
- **Namespace**: `App\Controllers`
- **Convención**: `controller/method/params`
- **Grupos**: `/admin`, `/profesores`, `/estudiantes`
- **Filtros**: `admin`, `auth`
- **Parámetros**: `:num`, `:any`

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🏠 RUTAS PRINCIPALES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Ruta Raíz
```php
$routes->get('/', 'Home::index');
```
**Propósito**: Página principal del sitio web
**Controlador**: `Home::index()`
**Vista**: `index.php`

### Rutas de Autenticación
```php
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');
```
**Propósito**: Sistema de login/logout
**Controlador**: `Auth`
**Funciones**: Mostrar formulario, procesar credenciales, cerrar sesión

### Rutas de Registro Público
```php
$routes->get('registro', 'RegistroController::index');
$routes->post('registro', 'RegistroController::registrar');
$routes->get('registro/getModalidades/(:num)', 'RegistroController::getModalidades/$1');
$routes->get('registro/getCategorias/(:num)', 'RegistroController::getCategorias/$1');
```
**Propósito**: Registro de estudiantes desde el frontend
**Controlador**: `RegistroController`
**Funcionalidad**: Formulario, carga dinámica de opciones

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🤖 RUTAS AJAX PARA CONTENIDO DINÁMICO
*********************************************************************************************************************************
*********************************************************************************************************************************

### Carga de Carreras
```php
$routes->get('ajax/oferta_academica_default', 'AjaxController::oferta_academica_default');
$routes->get('ajax/ciencia_datos', 'AjaxController::ciencia_datos');
$routes->get('ajax/profesorado_matematica', 'AjaxController::profesorado_matematica');
// ... más rutas similares para cada carrera
```
**Propósito**: Carga dinámica de vistas de carreras (SPA)
**Controlador**: `AjaxController`
**Funcionalidad**: Single Page Application sin recarga

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍💼 RUTAS DEL PANEL DE ADMINISTRADOR
*********************************************************************************************************************************
*********************************************************************************************************************************

### Grupo Administrador
```php
$routes->group('administrador', ['filter' => 'admin'], static function ($routes) {
    $routes->get('/', 'Administradores::index');
    $routes->get('administradores', 'Administradores::index');
```
**Propósito**: Panel principal del administrador
**Filtro**: `admin` (requiere permisos de administrador)

### Gestión de Usuarios
```php
$routes->get('usuarios', 'Usuarios::index');
$routes->post('usuarios/store', 'Usuarios::registrar');
$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
$routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
$routes->post('usuarios/delete/(:num)', 'Usuarios::delete/$1');
$routes->get('usuarios/search/(:num)', 'Usuarios::search/$1');
```
**Propósito**: CRUD completo de usuarios del sistema
**Parámetros**: `:num` (ID del usuario)

### Gestión de Estudiantes
```php
$routes->get('estudiantes', 'Estudiantes::index');
$routes->post('estudiantes/store', 'Estudiantes::store');
$routes->get('estudiantes/edit/(:num)', 'Estudiantes::edit/$1');
$routes->post('estudiantes/update/(:num)', 'Estudiantes::update/$1');
$routes->post('estudiantes/delete/(:num)', 'Estudiantes::delete/$1');
$routes->get('estudiantes/search/(:num)', 'Estudiantes::search/$1');
$routes->get('estudiantes/search/carrera/(:num)', 'Estudiantes::searchByCareer/$1');
```
**Propósito**: Gestión completa de estudiantes
**Funcionalidad**: CRUD + búsqueda por ID y carrera

### Gestión de Profesores
```php
$routes->get('profesores', 'Profesores::index');
$routes->post('profesores/store', 'Profesores::store');
$routes->get('profesores/edit/(:num)', 'Profesores::edit/$1');
$routes->post('profesores/update/(:num)', 'Profesores::update/$1');
$routes->post('profesores/delete/(:num)', 'Profesores::delete/$1');
$routes->get('profesores/search/(:num)', 'Profesores::search/$1');
$routes->get('profesores/searchByLegajo/(:any)', 'Profesores::searchByLegajo/$1');
```
**Propósito**: Gestión completa de profesores
**Parámetros**: `:num` (ID), `:any` (legajo)

### Gestión de Carreras
```php
$routes->get('carreras', 'RegistrarCarrera::index');
$routes->post('carreras/registrar', 'RegistrarCarrera::registrar');
$routes->post('carreras/store', 'RegistrarCarrera::store');
$routes->get('carreras/edit/(:num)', 'RegistrarCarrera::edit/$1');
$routes->post('carreras/update/(:num)', 'RegistrarCarrera::update/$1');
$routes->post('carreras/delete/(:num)', 'RegistrarCarrera::delete/$1');
$routes->get('carreras/search/(:num)', 'RegistrarCarrera::search/$1');
$routes->get('carreras/generar-codigo/(:any)', 'RegistrarCarrera::generarCodigo/$1');
```
**Propósito**: Gestión de carreras académicas
**Funcionalidad**: CRUD + generación automática de códigos

### Gestión de Categorías y Modalidades
```php
$routes->get('categorias', 'Categorias::index');
$routes->post('categorias/store', 'Categorias::store');
// ... rutas similares para modalidades
```
**Propósito**: Gestión de categorías y modalidades de carreras

### Gestión de Materias
```php
$routes->get('materias', 'Materias::index');
$routes->post('materias/store', 'Materias::store');
$routes->get('materias/generar-codigo/(:any)', 'Materias::generarCodigo/$1');
```
**Propósito**: Gestión de asignaturas

### Gestión de Roles
```php
$routes->get('rol', 'Rol::index');
$routes->post('rol/registrar', 'Rol::registrar');
// ... rutas CRUD para roles
```
**Propósito**: Gestión de roles de usuario

### Sistema de Alertas (Consultas)
```php
$routes->get('alertas', 'ConsultasAdmin::index');
$routes->get('alertas/count', 'ConsultasAdmin::countUnread');
$routes->post('alertas/mark-as-read/(:num)', 'ConsultasAdmin::markAsRead/$1');
```
**Propósito**: Gestión de consultas/contactos
**Funcionalidad**: Contador de no leídas, marcar como resueltas

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🏫 RUTAS DEL PANEL DE PROFESORES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Grupo Profesores
```php
$routes->group('profesores', static function ($routes) {
    $routes->get('dashboard', 'Profesores::dashboard');
    $routes->get('edit/(:num)', 'Profesores::edit/$1');
    $routes->post('update/(:num)', 'Profesores::update/$1');
    // ... más rutas para gestión del perfil
```
**Propósito**: Dashboard y gestión de perfil de profesores

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 👨‍🎓 RUTAS DEL PANEL DE ESTUDIANTES
*********************************************************************************************************************************
*********************************************************************************************************************************

### Grupo Estudiantes
```php
$routes->group('estudiantes', static function ($routes) {
    $routes->get('dashboard', 'Estudiantes::dashboard');
    // ... rutas CRUD para estudiantes
```
**Propósito**: Dashboard y gestión de perfil de estudiantes

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 🔌 RUTAS API
*********************************************************************************************************************************
*********************************************************************************************************************************

### Endpoints API
```php
$routes->group('api', static function ($routes) {
    $routes->get('get_carreras', 'ApiController::getCarreras');
    $routes->get('get_categorias', 'ApiController::getCategorias');
    $routes->get('get_modalidades', 'ApiController::getModalidades');
});
```
**Propósito**: Endpoints para poblar selects dinámicos

---

*********************************************************************************************************************************
**********************************************************************************************************************************
## 💬 RUTA DE CONSULTAS
*********************************************************************************************************************************
*********************************************************************************************************************************

### Envío de Consultas
```php
$routes->post('consultas/enviar', 'Consultas::enviar');
```
**Propósito**: Envío de consultas desde formularios de contacto
**Controlador**: `Consultas::enviar()`
**Funcionalidad**: AJAX con validación CSRF

---

## 🔧 Características Técnicas

### 🛡️ Seguridad
- **Filtros**: `admin` para rutas protegidas
- **CSRF Protection**: Tokens en formularios POST
- **Validación**: Parámetros `:num`, `:any`

### 🔄 Métodos HTTP
- **GET**: Lectura de datos, formularios
- **POST**: Creación/actualización de datos

### 📊 Parámetros Dinámicos
- **`:num`**: Solo números (IDs)
- **`:any`**: Cualquier carácter (legajos, códigos)

### 🎯 Grupos de Rutas
- **Sin grupo**: Rutas públicas
- **`administrador`**: Panel admin (filtrado)
- **`profesores`**: Panel profesores
- **`estudiantes`**: Panel estudiantes
- **`api`**: Endpoints API

### ⚡ Optimizaciones
- **Rutas explícitas**: Evita conflictos
- **Grupos lógicos**: Organización modular
- **Filtros apropiados**: Seguridad granular

---

## 📈 Conclusión

Esta documentación proporciona una visión completa del sistema de rutas de **App Universidad**. Las rutas están organizadas de manera lógica, con una clara separación entre públicos y privados, y utilizan las mejores prácticas de CodeIgniter 4.

**Recomendaciones**:
- Mantener consistencia en la nomenclatura
- Usar filtros apropiados para seguridad
- Documentar nuevas rutas agregadas
- Considerar API versioning para futuras expansiones
- Implementar rate limiting en endpoints críticos
