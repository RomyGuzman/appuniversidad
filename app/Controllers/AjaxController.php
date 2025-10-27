<?php

namespace App\Controllers;

use App\Models\CarreraModel; // 1. Importamos el modelo de Carrera

/**
 * AjaxController
 *
 * Este controlador está dedicado exclusivamente a manejar las peticiones AJAX
 * que provienen del frontend (app.js). Su propósito es devolver fragmentos de HTML (vistas parciales)
 * sin recargar la página completa, mejorando la experiencia del usuario.
 */
class AjaxController extends BaseController
{
    /**
     * Carga la vista por defecto de la oferta académica.
     * Es llamado cuando el usuario quiere volver a la lista principal de carreras.
     *
     * @return string Vista parcial 'oferta_academica_default.php'.
     */
    public function oferta_academica_default()
    {
        return view('templates/oferta_academica_default');
    }

    /**
     * Carga la vista detallada de la carrera "Ciencia de Datos".
     * Es llamado desde la barra de navegación o la tarjeta de la carrera.
     *
     * @return string Vista parcial 'ciencia_datos.php'.
     */
    public function ciencia_datos()
    {
        // La vista no requiere datos de la base de datos, es contenido estático
        return view('Vistas_Dinamicas/ciencia_datos');
    }

    /**
     * Carga la vista detallada de la carrera "Programación Web".
     * Es llamado desde la barra de navegación o la tarjeta de la carrera.
     *
     * @return string Vista parcial 'programacion_web_content.php'.
     */
    public function programacion_web()
    {
        return view('programacion_web_content');
    }

    /**
     * Vista de prueba para depurar la funcionalidad de AJAX.
     * @return string
     */
    public function test()
    {
        return view('ajax_test');
    }

    /**
     * Carga la vista detallada de la carrera "Profesorado de Inglés".
     * @return string Vista parcial 'profesorado_ingles.php'.
     */
    public function profesorado_ingles()
    {
        return view('Vistas_Dinamicas/profesorado_ingles');
    }

    /**
     * Carga la vista detallada de la carrera "Profesorado de Matemáticas".
     * @return string Vista parcial 'profesorado_matematica.php'.
     */
    public function profesorado_matematica()
    {
        return view('Vistas_Dinamicas/profesorado_matematica');
    }

    /**
     * Carga la vista detallada de la carrera "Profesorado en Educación Inicial".
     * @return string Vista parcial 'educacion_inicial.php'.
     */
    public function educacion_inicial()
    {
        return view('Vistas_Dinamicas/educacion_inicial');
    }

    /**
     * Carga la vista detallada de la carrera "Enfermería".
     * @return string Vista parcial 'enfermeria.php'.
     */
    public function enfermeria()
    {
        return view('Vistas_Dinamicas/enfermeria');
    }

    /**
     * Carga la vista detallada de la carrera "Seguridad e Higiene".
     * @return string Vista parcial 'seguridad_higiene.php'.
     */
    public function seguridad_higiene()
    {
        return view('Vistas_Dinamicas/seguridad_higiene');
    }
}
