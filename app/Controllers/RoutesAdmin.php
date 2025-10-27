<?php

/**
 * @var \CodeIgniter\Router\RouteCollection $routes
 */

// --- Grupo de Rutas para el Panel de Administración ---
// Todas las rutas aquí definidas tendrán el prefijo '/admin' y su namespace será 'App\Controllers\Admin'
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], static function ($routes) {
    
    // Redirección principal del admin al dashboard o a la primera sección
    $routes->get('/', 'Estudiantes::index');

    // --- Rutas para el CRUD de Estudiantes ---
    $routes->get('estudiantes', 'Estudiantes::index');
    $routes->post('estudiantes/registrar', 'Estudiantes::registrar');
    $routes->get('estudiantes/edit/(:num)', 'Estudiantes::edit/$1');
    $routes->post('estudiantes/update/(:num)', 'Estudiantes::update/$1');
    $routes->get('estudiantes/delete/(:num)', 'Estudiantes::delete/$1');
    $routes->get('estudiantes/search/(:num)', 'Estudiantes::search/$1');
    $routes->get('estudiantes/searchByCareer/(:num)', 'Estudiantes::searchByCareer/$1');

    // --- Rutas para el CRUD de Carreras ---
    // Renombramos la ruta base a 'carreras' para mayor claridad
    $routes->get('carreras', 'RegistrarCarrera::index');
    $routes->post('carreras/registrar', 'RegistrarCarrera::registrar');
    $routes->get('carreras/edit/(:num)', 'RegistrarCarrera::edit/$1');
    $routes->post('carreras/update/(:num)', 'RegistrarCarrera::update/$1');
    $routes->get('carreras/delete/(:num)', 'RegistrarCarrera::delete/$1');
    $routes->get('carreras/search/(:num)', 'RegistrarCarrera::search/$1');
    $routes->get('carreras/generar-codigo/(:any)', 'RegistrarCarrera::generarCodigoAjax/$1');

    // --- Rutas para el CRUD de Categorías ---
    $routes->get('categorias', 'Categorias::index');
    $routes->post('categorias/registrar', 'Categorias::registrar');
    $routes->get('categorias/edit/(:num)', 'Categorias::edit/$1');
    $routes->post('categorias/update/(:num)', 'Categorias::update/$1');
    $routes->get('categorias/delete/(:num)', 'Categorias::delete/$1');
    $routes->get('categorias/search/(:num)', 'Categorias::search/$1');

    // --- Rutas para el Sistema de Alertas ---
    $routes->get('alertas', 'ConsultasAdmin::index');
    $routes->get('alertas/count', 'ConsultasAdmin::getUnreadCount');
    $routes->post('alertas/mark-as-read/(:num)', 'ConsultasAdmin::markAsRead/$1');

});