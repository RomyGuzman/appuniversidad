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
        $data = [
            'titulo' => 'Tecnicatura Superior en Ciencia de Datos e Inteligencia Artificial',
            'descripcion' => 'Formá parte de la revolución tecnológica. Aprendé a transformar datos en decisiones estratégicas y desarrollá soluciones inteligentes para los desafíos del futuro.',
            'imagen' => 'https://images.unsplash.com/photo-1555949963-aa79dcee981c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80',
            'estadisticas' => [
                ['numero' => '94%', 'label' => 'Inserción laboral'],
                ['numero' => '+200%', 'label' => 'Crecimiento de empleo'],
                ['numero' => '$150k', 'label' => 'Salario inicial promedio'],
                ['numero' => '+50', 'label' => 'Empresas asociadas']
            ],
            'que_aprendes' => [
                'Programación en Python, R y SQL',
                'Análisis estadístico y machine learning',
                'Procesamiento de grandes volúmenes de datos',
                'Desarrollo de modelos predictivos',
                'Visualización de datos y storytelling',
                'Ética en IA y protección de datos'
            ],
            'plan_estudios' => [
                'Primer Año' => [
                    'Introducción a la Programación',
                    'Matemática para Ciencia de Datos',
                    'Estadística Descriptiva e Inferencial',
                    'Bases de Datos y SQL',
                    'Análisis Exploratorio de Datos',
                    'Ética y Legislación en Tecnología'
                ],
                'Segundo Año' => [
                    'Machine Learning Supervisado',
                    'Machine Learning No Supervisado',
                    'Procesamiento de Lenguaje Natural',
                    'Visualización de Datos',
                    'Big Data y Cloud Computing',
                    'Desarrollo de APIs y Microservicios'
                ],
                'Tercer Año' => [
                    'Deep Learning y Redes Neuronales',
                    'Computer Vision',
                    'Sistemas de Recomendación',
                    'Procesamiento de Series Temporales',
                    'Gobernanza de Datos',
                    'Práctica Profesional Supervisada'
                ]
            ],
            'perfil_egresado' => [
                'tecnicas' => 'Serás capaz de diseñar, implementar y mantener sistemas de análisis de datos, desarrollar modelos de machine learning y crear soluciones basadas en inteligencia artificial para resolver problemas complejos en diversos contextos.',
                'laboral' => 'Podrás trabajar como científico de datos, analista de datos, especialista en machine learning, consultor en inteligencia artificial, desarrollador de soluciones IA, entre otras posiciones de alta demanda en el mercado laboral actual.'
            ],
            'areas_desempeno' => [
                ['titulo' => 'Empresas tecnológicas', 'desc' => 'Startups, empresas de software, consultoras IT'],
                ['titulo' => 'Sector industrial', 'desc' => 'Manufactura, logística, optimización de procesos'],
                ['titulo' => 'Sector salud', 'desc' => 'Diagnóstico médico, investigación clínica'],
                ['titulo' => 'Sector financiero', 'desc' => 'Bancos, fintech, análisis de riesgos'],
                ['titulo' => 'Comercio y retail', 'desc' => 'E-commerce, marketing digital, recomendación'],
                ['titulo' => 'Investigación y desarrollo', 'desc' => 'Centros de investigación, universidades']
            ],
            'testimonios' => [
                ['texto' => 'La carrera me dio las herramientas para conseguir mi primer trabajo como Data Analyst antes de terminar el segundo año. Los proyectos prácticos son increíbles.', 'autor' => 'María González', 'rol' => 'Estudiante de 3er año'],
                ['texto' => 'Los profesores son profesionales en actividad que traen casos reales de la industria. Aprendí Python, machine learning y ahora trabajo desarrollando modelos predictivos.', 'autor' => 'Carlos Rodríguez', 'rol' => 'Egresado 2023'],
                ['texto' => 'La combinación de teoría y práctica es perfecta. Los laboratorios están equipados con la última tecnología y tenemos acceso a datasets reales para trabajar.', 'autor' => 'Ana López', 'rol' => 'Estudiante de 2do año']
            ],
            'whatsapp_link' => 'https://wa.me/5491134567890?text=Hola,%20me%20interesa%20la%20Tecnicatura%20en%20Ciencia%20de%20Datos%20e%20IA'
        ];
        return view('Vistas_Dinamicas/ciencia_datos', $data);
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
