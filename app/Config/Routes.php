<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- RUTA PRINCIPAL ---
$routes->get('/', 'Home::index');

// --- Rutas de Autenticación ---
// Muestra el formulario de login
$routes->get('login', 'Auth::login');
// Procesa el intento de login
$routes->post('login', 'Auth::attemptLogin');
// Cierra la sesión del usuario
$routes->get('logout', 'Auth::logout');

// --- Rutas de Registro Público ---
// Muestra el formulario de registro público (si se accede directamente)
$routes->get('registro', 'RegistroController::index');
// Procesa el envío del formulario de registro
$routes->post('registro', 'RegistroController::registrar');
// Métodos AJAX para cargar modalidades y categorías
$routes->get('registro/getModalidades/(:num)', 'RegistroController::getModalidades/$1');
$routes->get('registro/getCategorias/(:num)', 'RegistroController::getCategorias/$1');

   

// --- RUTAS PARA CARGA DE CONTENIDO DINÁMICO (AJAX) ---
// CORRECCIÓN: Se implementan las rutas explícitas con guiones bajos, siguiendo la documentación.
// Esto asegura que cada URL de AJAX apunte directamente a su método correspondiente en AjaxController.
$routes->get('ajax/oferta_academica_default', 'AjaxController::oferta_academica_default');
$routes->get('ajax/ciencia_datos', 'AjaxController::ciencia_datos');
$routes->get('ajax/profesorado_matematica', 'AjaxController::profesorado_matematica');
$routes->get('ajax/seguridad_higiene', 'AjaxController::seguridad_higiene');
$routes->get('ajax/enfermeria', 'AjaxController::enfermeria');
$routes->get('ajax/profesorado_ingles', 'AjaxController::profesorado_ingles');
$routes->get('ajax/educacion_inicial', 'AjaxController::educacion_inicial');
$routes->get('ajax/registro', 'AjaxController::registro');

// --- RUTAS DEL PANEL DE ADMINISTRADOR ---
$routes->group('administrador', ['filter' => 'admin'], static function ($routes) {
    // Dashboard principal del admin
    $routes->get('/', 'Administradores::index');
    $routes->get('administradores', 'Administradores::index');

    // --- Gestión de Usuarios ---
    $routes->get('usuarios', 'Usuarios::index');
    $routes->post('usuarios/store', 'Usuarios::registrar');
    $routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');
    $routes->post('usuarios/update/(:num)', 'Usuarios::update/$1');
    $routes->post('usuarios/delete/(:num)', 'Usuarios::delete/$1');
    $routes->get('usuarios/search/(:num)', 'Usuarios::search/$1');

    // --- Gestión de Estudiantes ---
    $routes->get('estudiantes', 'Estudiantes::index');
    $routes->post('estudiantes/store', 'Estudiantes::store');
    $routes->get('estudiantes/edit/(:num)', 'Estudiantes::edit/$1');
    $routes->post('estudiantes/update/(:num)', 'Estudiantes::update/$1');
    $routes->post('estudiantes/delete/(:num)', 'Estudiantes::delete/$1');
    $routes->get('estudiantes/search/(:num)', 'Estudiantes::search/$1');
    $routes->get('estudiantes/search/carrera/(:num)', 'Estudiantes::searchByCareer/$1');

    // --- Gestión de Profesores ---
    $routes->get('profesores', 'Profesores::index');
    $routes->post('profesores', 'Profesores::create');
    $routes->post('profesores/buscarProfesorPorNombre', 'Profesores::buscarProfesorPorNombre');
    $routes->post('profesores/store', 'Profesores::store');
    $routes->get('profesores/edit/(:num)', 'Profesores::edit/$1');
    $routes->post('profesores/update/(:num)', 'Profesores::update/$1');
    $routes->post('profesores/delete/(:num)', 'Profesores::delete/$1');
    $routes->get('profesores/search/(:num)', 'Profesores::search/$1');
    $routes->get('profesores/searchByLegajo/(:any)', 'Profesores::searchByLegajo/$1');
    $routes->post('profesores/assign-materia', 'Profesores::assignMateria');

    // --- Gestión de Carreras ---
    $routes->get('carreras', 'RegistrarCarrera::index');
    $routes->post('carreras/registrar', 'RegistrarCarrera::registrar');
    $routes->post('carreras/store', 'RegistrarCarrera::store');
    $routes->get('carreras/edit/(:num)', 'RegistrarCarrera::edit/$1');
    $routes->post('carreras/update/(:num)', 'RegistrarCarrera::update/$1');
    $routes->post('carreras/delete/(:num)', 'RegistrarCarrera::delete/$1');
    $routes->get('carreras/search/(:num)', 'RegistrarCarrera::search/$1');
    $routes->get('carreras/generar-codigo/(:any)', 'RegistrarCarrera::generarCodigo/$1');

    // --- Gestión de Categorías ---
    $routes->get('categorias', 'Categorias::index');
    $routes->post('categorias/store', 'Categorias::store');
    $routes->get('categorias/edit/(:num)', 'Categorias::edit/$1');
    $routes->post('categorias/update/(:num)', 'Categorias::update/$1');
    $routes->post('categorias/delete/(:num)', 'Categorias::delete/$1');
    $routes->get('categorias/search/(:num)', 'Categorias::search/$1');

    // --- Gestión de Materias ---
    $routes->get('materias', 'Materias::index');
    $routes->post('materias/registrar', 'Materias::registrar');
    $routes->post('materias/store', 'Materias::store');
    $routes->get('materias/edit/(:num)', 'Materias::edit/$1');
    $routes->post('materias/update/(:num)', 'Materias::update/$1');
    $routes->post('materias/delete/(:num)', 'Materias::delete/$1');
    $routes->get('materias/search/(:num)', 'Materias::search/$1');
    $routes->get('materias/generar-codigo/(:any)', 'Materias::generarCodigo/$1');

    // --- Gestión de Modalidades ---
    $routes->get('modalidades', 'Modalidades::index');
    $routes->post('modalidades/store', 'Modalidades::store');
    $routes->get('modalidades/edit/(:num)', 'Modalidades::edit/$1');
    $routes->post('modalidades/update/(:num)', 'Modalidades::update/$1');
    $routes->post('modalidades/delete/(:num)', 'Modalidades::delete/$1');
    $routes->get('modalidades/search/(:num)', 'Modalidades::search/$1');

    // --- Gestión de Roles ---
    $routes->get('rol', 'Rol::index');
    $routes->post('rol/registrar', 'Rol::registrar');
    $routes->get('rol/edit/(:num)', 'Rol::edit/$1');
    $routes->post('rol/update/(:num)', 'Rol::update/$1');
    $routes->post('rol/delete/(:num)', 'Rol::delete/$1');
    $routes->get('rol/search/(:num)', 'Rol::search/$1');

    // --- Sistema de Alertas (Consultas) ---
    $routes->get('alertas', 'ConsultasAdmin::index');
    $routes->get('alertas/count', 'ConsultasAdmin::getUnreadCount');
    $routes->post('alertas/mark-as-read/(:num)', 'ConsultasAdmin::markAsRead/$1');
});

// --- RUTAS DEL PANEL DE PROFESORES ---
$routes->group('profesores', static function ($routes) {
    // Dashboard principal del profesor
    $routes->get('dashboard', 'Profesores::dashboard');

    // --- Gestión de Profesores (CRUD para su propio perfil, si aplica) ---
    $routes->get('edit/(:num)', 'Profesores::edit/$1');
    $routes->post('update/(:num)', 'Profesores::update/$1');
    $routes->get('search/(:num)', 'Profesores::search/$1');
    $routes->get('searchByLegajo/(:any)', 'Profesores::searchByLegajo/$1');
});

// --- RUTAS DEL PANEL DE ESTUDIANTES ---
$routes->group('estudiantes', static function ($routes) {
    // Dashboard principal del estudiante
    $routes->get('dashboard', 'Estudiantes::dashboard');

    // --- Gestión de Estudiantes (CRUD para administradores, pero las rutas AJAX están aquí) ---
    $routes->get('/', 'Estudiantes::index'); // Asumo que hay una lista de estudiantes
    $routes->post('store', 'Estudiantes::store');
    $routes->get('edit/(:num)', 'Estudiantes::edit/$1');
    $routes->post('update/(:num)', 'Estudiantes::update/$1');
    $routes->post('delete/(:num)', 'Estudiantes::delete/$1');
    $routes->get('search/(:num)', 'Estudiantes::search/$1');
    $routes->get('search/carrera/(:num)', 'Estudiantes::searchByCareer/$1');

    // NUEVO: Ruta para inscripción AJAX (sin filtros CSRF)
    $routes->post('inscribir', 'Estudiantes::inscribir');
});

// --- RUTAS PARA API (si las usas para poblar selects, etc.) ---
$routes->group('api', static function ($routes) {
    $routes->get('get_carreras', 'ApiController::getCarreras');
    $routes->get('get_categorias', 'ApiController::getCategorias');
    $routes->get('get_modalidades', 'ApiController::getModalidades');
});

// --- RUTA PARA CARRERAS (REDIRECCIÓN DESDE ADMINISTRADOR) ---
$routes->get('carreras', 'RegistrarCarrera::index');

// --- RUTA PARA ENVIAR CONSULTAS ---
$routes->post('consultas/enviar', 'Consultas::enviar');

// --- RUTA PARA LIMPIAR NOTIFICACIONES ---
$routes->post('notificaciones/limpiar', 'Notificaciones::limpiar');


