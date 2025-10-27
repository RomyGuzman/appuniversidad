<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Controllers;

// Importa las clases necesarias de CodeIgniter.
use CodeIgniter\Controller; // Importa la clase Controller de CodeIgniter (la clase base para todos los controladores)
use CodeIgniter\HTTP\CLIRequest;  // Importa la clase CLIRequest (para peticiones desde la línea de comandos)
use CodeIgniter\HTTP\IncomingRequest; // Importa la clase IncomingRequest (para peticiones HTTP normales)
use CodeIgniter\HTTP\RequestInterface; // Importa la interfaz RequestInterface (para tipar la propiedad $request)
use CodeIgniter\HTTP\ResponseInterface; // Importa la interfaz ResponseInterface (para tipar la respuesta)
use Psr\Log\LoggerInterface; // Importa la interfaz LoggerInterface (para tipar el logger)

/**
 * --- Clase BaseController ---
 *
 * Este es un controlador especial que sirve como "padre" para todos los demás controladores de la aplicación.
 * Proporciona un lugar centralizado para cargar componentes (como helpers o servicios) y ejecutar funciones
 * que son necesarias en toda la aplicación.
 *
 * Cualquier controlador nuevo debe extender esta clase: `class MiControlador extends BaseController`
 */
abstract class BaseController extends Controller 
{
    /**
     * Instancia del objeto principal de la Petición (Request).
     * Permite acceder a la información de la petición HTTP actual (datos POST, GET, etc.).
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * Un array de "helpers" que se cargarán automáticamente cuando se instancie cualquier controlador
     * que herede de BaseController. Los helpers son colecciones de funciones globales.
     * @var list<string>
     */
    protected $helpers = ['url']; // Puedes agregar helpers aquí, por ejemplo: ['url', 'form'] (para que )
                                // estén disponibles en todos los controladores.
                                //helpers son colecciones de funciones globales.
    /**
     * Propiedad para almacenar la instancia del servicio de sesión.
     * Es buena práctica declararla para evitar la creación de propiedades dinámicas,
     * que está obsoleto en PHP 8.2.
     */
    protected $session;

    /**
     * El método initController() se ejecuta automáticamente justo después de que el controlador
     * es instanciado. Es el lugar perfecto para inicializar servicios o cargar dependencias.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Llama al método initController del controlador padre (de CodeIgniter). No se debe editar esta línea.
        parent::initController($request, $response, $logger);

        // Carga el servicio de sesión de CodeIgniter y lo asigna a la propiedad $this->session.
        // Ahora, cualquier controlador que herede de BaseController podrá acceder a la sesión
        // usando `$this->session`. Esto es útil para manejar mensajes flash, datos de usuario, etc.
        $this->session = service('session');
    }
}
