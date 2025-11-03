<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AjaxController extends BaseController
{
    /**
     * Devuelve la vista por defecto de la oferta académica.
     */
    public function oferta_academica_default()
    {
        return view('templates/oferta_academica_default');
    }

    /**
     * Devuelve la vista de detalle para Ciencia de Datos.
     */
    public function ciencia_datos()
    {
        // Pasa los datos específicos de la carrera a la vista
        $data = [
            'titulo' => 'Tecnicatura Superior en Ciencia de Datos e Inteligencia Artificial',
            'descripcion' => 'Formá parte de la revolución tecnológica. Aprendé a transformar datos en decisiones estratégicas y desarrollá soluciones inteligentes para los desafíos del futuro.',
            'imagen' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'
        ];
        return view('Vistas_Dinamicas/ciencia_datos', $data);
    }

    /**
     * Devuelve la vista de detalle para Profesorado de Matemática.
     */
    public function profesorado_matematica()
    {
        return view('Vistas_Dinamicas/profesorado_matematica');
    }

    /**
     * Devuelve la vista de detalle para Profesorado de Inglés.
     */
    public function profesorado_ingles()
    {
        return view('Vistas_Dinamicas/profesorado_ingles');
    }

    public function seguridad_higiene()
    {
        return view('Vistas_Dinamicas/seguridad_higiene');
    }

    public function enfermeria()
    {
        return view('Vistas_Dinamicas/enfermeria');
    }

    public function educacion_inicial()
    {
        return view('Vistas_Dinamicas/educacion_inicial');
    }

    /**
     * Devuelve la vista del formulario de registro.
     * Esta es la vista que se cargará dinámicamente.
     */
    public function registro()
    {
        // CORRECCIÓN: Se necesita cargar los datos para los dropdowns del formulario de registro.
        $carreraModel = new \App\Models\CarreraModel();
        $data['carreras'] = $carreraModel->orderBy('nombre_carrera', 'ASC')->findAll(); // Usamos findAll() para obtener todas las carreras
        return view('registro', $data);
    }

    /**
     * Devuelve una vista de prueba simple para la sección de test AJAX.
     */
    public function test() {
        return '<p>La sección de prueba AJAX ha cargado correctamente.</p>';
    }
}