<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Controlador para manejar las consultas de estudiantes y profesores al administrador.
 */
class Consultas extends BaseController
{
    /**
     * Método para enviar una consulta desde el dashboard de estudiante o profesor.
     * Guarda la consulta en la base de datos con los datos del usuario y la sesión.
     */
    public function enviar()
    {
        // Validar que sea una petición POST
        if (!$this->request->is('post')) {
            return redirect()->back()->with('error', 'Método no permitido.');
        }

        // Obtener datos del formulario
        $asunto = $this->request->getPost('asunto');
        $mensaje = $this->request->getPost('mensaje');
        
        // Obtener datos de la sesión
        $session = session();
        $usuario_id = $session->get('id_usuario');
        $rol_id = $session->get('rol_id');
        $email = $session->get('email'); // Asumiendo que el email está en la sesión

        // Validar datos básicos
        if (empty($asunto) || empty($mensaje) || empty($usuario_id) || empty($rol_id)) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }

        // Preparar datos para insertar en consultas_admin
        $data = [
            'email_usuario' => $email ?? 'no-email@sistema.com', // Usar el email de la sesión
            'usuario_id' => $usuario_id,
            'rol_id' => $rol_id,
            'mensaje' => $mensaje,
            'asunto' => $asunto,
            'estado' => 'pendiente',
            'fecha_creacion' => date('Y-m-d H:i:s')
        ];

        // Insertar en la base de datos
        $builder = \Config\Database::connect()->table('consultas_admin');
        if ($builder->insert($data)) {
            return redirect()->back()->with('success', 'Consulta enviada correctamente. El administrador la revisará pronto.');
        } else {
            return redirect()->back()->with('error', 'Error al enviar la consulta. Inténtalo de nuevo.');
        }
    }
}
