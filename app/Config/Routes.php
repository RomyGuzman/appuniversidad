<?php

namespace Config;

$routes = Services::routes();



$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

use CodeIgniter\Router\RouteCollection;
/**
 * @var RouteCollection $routes

 */

// --- RUTAS PRINCIPALES DE VISTAS ---
// Estas rutas se encargan de mostrar las páginas principales de cada módulo.
$routes->get('/', 'Home::index'); // Muestra la página de inicio institucional.
$routes->get('/home/index', 'Home::index'); // Alias para la página de inicio.

// --- RUTAS DE LOGIN (usando el controlador Auth inteligente) ---
$routes->get('login', 'Auth::login', ['as' => 'login']);
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout', ['as' => 'logout']);

// --- RUTAS PARA CARGA DE CONTENIDO DINÁMICO (AJAX) ---
// Estas rutas son usadas por JavaScript para cargar el contenido de las carreras dinámicamente.
$routes->group('ajax', static function ($routes) {
    $routes->get('oferta_academica_default', 'AjaxController::oferta_academica_default');
    $routes->get('ciencia_datos', 'AjaxController::ciencia_datos');
    $routes->get('programacion_web', 'AjaxController::programacion_web');
    $routes->get('profesorado_matematica', 'AjaxController::profesorado_matematica');
    $routes->get('profesorado_ingles', 'AjaxController::profesorado_ingles');
    $routes->get('educacion_inicial', 'AjaxController::educacion_inicial');
    $routes->get('enfermeria', 'AjaxController::enfermeria');
    $routes->get('seguridad_higiene', 'AjaxController::seguridad_higiene');
});

// --- RUTAS DE GESTIÓN (CRUDs) ---

// --- RUTAS DE ACCESO A DASHBOARDS (para compatibilidad) ---
$routes->get('acceso/estudiantes', 'Estudiantes::dashboard');
$routes->get('acceso/profesores', 'Profesores::dashboard');

// --- ESTUDIANTES ---
$routes->get('estudiantes', 'Estudiantes::index'); // Lista principal de estudiantes
$routes->get('estudiantes/dashboard', 'Estudiantes::dashboard'); // Dashboard del Estudiante
$routes->get('profesores/dashboard', 'Profesores::dashboard');   // Dashboard del Profesor
$routes->post('estudiantes/registrar', 'Estudiantes::registrar');
$routes->get('estudiantes/edit/(:num)', 'Estudiantes::edit/$1');
$routes->post('estudiantes/update/(:num)', 'Estudiantes::update/$1');
$routes->post('estudiantes/delete/(:num)', 'Estudiantes::delete/$1');
$routes->get('estudiantes/search/(:num)', 'Estudiantes::search/$1');
$routes->get('estudiantes/search/carrera/(:num)', 'Estudiantes::searchByCareer/$1');

// --- CARRERAS ---
$routes->get('carreras', 'RegistrarCarrera::index');
$routes->post('carreras/registrar', 'RegistrarCarrera::registrar');
$routes->get('carreras/edit/(:num)', 'RegistrarCarrera::edit/$1');
$routes->post('carreras/update/(:num)', 'RegistrarCarrera::update/$1');
$routes->post('carreras/delete/(:num)', 'RegistrarCarrera::delete/$1');
$routes->get('carreras/search/(:num)', 'RegistrarCarrera::search/$1');
$routes->get('carreras/generar-codigo/(:segment)', 'RegistrarCarrera::generarCodigoAjax/$1');

// --- CATEGORÍAS ---
$routes->get('categorias', 'Categorias::index');
$routes->post('categorias/registrar', 'Categorias::registrar');
$routes->get('categorias/edit/(:num)', 'Categorias::edit/$1');
$routes->post('categorias/update/(:num)', 'Categorias::update/$1');
$routes->post('categorias/delete/(:num)', 'Categorias::delete/$1');
$routes->get('categorias/search/(:num)', 'Categorias::search/$1');

// --- ADMINISTRADORES ---
$routes->get('administradores', 'Administradores::index');
$routes->post('administradores/registrar', 'Administradores::registrar');
$routes->get('administradores/edit/(:num)', 'Administradores::edit/$1');
$routes->post('administradores/update/(:num)', 'Administradores::update/$1');
$routes->post('administradores/delete/(:num)', 'Administradores::delete/$1');
$routes->get('administradores/search/(:num)', 'Administradores::search/$1');

// --- PROFESORES ---
$routes->get('profesores', 'Profesores::index');
$routes->post('profesores/registrar', 'Profesores::registrar');
$routes->get('profesores/edit/(:num)', 'Profesores::edit/$1');
$routes->post('profesores/update/(:num)', 'Profesores::update/$1');
$routes->post('profesores/delete/(:num)', 'Profesores::delete/$1');
$routes->get('profesores/search/(:num)', 'Profesores::search/$1');
$routes->post('profesores/guardar-notas', 'Profesores::guardarNotas');
$routes->get('profesores/get-estudiantes-materia/(:num)', 'Profesores::getEstudiantesMateria/$1');
$routes->post('profesores/guardar-asistencia', 'Profesores::guardarAsistencia');
$routes->get('profesores/get-asistencia-materia/(:num)', 'Profesores::getAsistenciaMateria/$1');
$routes->get('profesores/get-asistencias-mensuales/(:num)/(:num)/(:num)', 'Profesores::getAsistenciasMensuales/$1/$2/$3');
$routes->post('profesores/guardar-asistencias-mensuales', 'Profesores::guardarAsistenciasMensuales');

// --- NUEVAS RUTAS PARA VISTA MENSUAL DE ASISTENCIA ---
$routes->get('profesores/asistencia-mensual/(:num)', 'Profesores::asistenciaMensual/$1');
$routes->get('profesores/get-eventos-asistencia/(:num)', 'Profesores::getEventosAsistencia/$1');
$routes->get('profesores/get-estadisticas-mes/(:num)', 'Profesores::getEstadisticasMes/$1');
$routes->get('profesores/get-resumen-estudiantes/(:num)', 'Profesores::getResumenEstudiantes/$1');

// --- RUTAS PARA ADMINISTRADOR ---
$routes->get('administrador/estudiantes', 'Estudiantes::index');
$routes->get('administrador/profesores', 'Profesores::index');
$routes->get('administrador/carreras', 'RegistrarCarrera::index');
$routes->get('administrador/categorias', 'Categorias::index');
$routes->get('administrador/administradores', 'Administradores::index');
$routes->get('administrador/modalidades', 'Modalidades::index');
$routes->get('administrador/rol', 'Rol::index');
$routes->get('administrador/materias', 'Materias::index');
$routes->get('administrador/usuarios', 'Usuarios::index');
$routes->post('administrador/usuarios', 'Usuarios::registrar');
$routes->get('administrador/usuarios/edit/(:num)', 'Usuarios::edit/$1');
$routes->post('administrador/usuarios/update/(:num)', 'Usuarios::update/$1');
$routes->post('administrador/usuarios/delete/(:num)', 'Usuarios::delete/$1');

// --- RUTAS PARA ALERTAS (CONSULTAS ADMIN) ---
$routes->get('administrador/alertas', 'ConsultasAdmin::index');
$routes->get('administrador/alertas/count', 'ConsultasAdmin::getUnreadCount');
$routes->post('administrador/alertas/mark-as-read/(:num)', 'ConsultasAdmin::markAsRead/$1');

// --- RUTAS CRUD PARA NUEVOS MÓDULOS ---
$routes->get('rol', 'Rol::index');
$routes->post('rol/registrar', 'Rol::registrar');
$routes->get('rol/edit/(:num)', 'Rol::edit/$1');
$routes->post('rol/update/(:num)', 'Rol::update/$1');
$routes->post('rol/delete/(:num)', 'Rol::delete/$1');
$routes->get('rol/search/(:num)', 'Rol::search/$1');

$routes->get('usuarios', 'Usuarios::index');
$routes->post('usuarios/registrar', 'Usuarios::registrar');
$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
$routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
$routes->post('usuarios/delete/(:num)', 'Usuarios::delete/$1');
$routes->get('usuarios/search/(:num)', 'Usuarios::search/$1');

$routes->get('materias', 'Materias::index');
$routes->post('materias/registrar', 'Materias::registrar');
$routes->get('materias/edit/(:num)', 'Materias::edit/$1');
$routes->post('materias/update/(:num)', 'Materias::update/$1');
$routes->post('materias/delete/(:num)', 'Materias::delete/$1');
$routes->get('materias/search/(:num)', 'Materias::search/$1');
$routes->get('materias/generar-codigo/(:segment)', 'Materias::generarCodigo/$1');

$routes->get('modalidades', 'Modalidades::index');
$routes->post('modalidades/registrar', 'Modalidades::registrar');
$routes->get('modalidades/edit/(:num)', 'Modalidades::edit/$1');
$routes->post('modalidades/update/(:num)', 'Modalidades::update/$1');
$routes->post('modalidades/delete/(:num)', 'Modalidades::delete/$1');
$routes->get('modalidades/search/(:num)', 'Modalidades::search/$1');

// --- RUTAS PARA CONSULTAS ---
$routes->post('consultas/enviar', 'Consultas::enviar');
