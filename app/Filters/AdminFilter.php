<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado (ajusta 'id_usuario' si usas otro nombre de sesión)
        if (!session()->get('id_usuario')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión.');
        }

        // Verificar si tiene rol de admin o superadmin (6 = Superadmin, 7 = Administrador)
        $rolId = session()->get('rol_id');
        if (!in_array($rolId, [6, 7])) {
            return redirect()->to('/login')->with('error', 'Acceso denegado. Solo administradores.');
        }

        // Opcional: Depuración (quita en producción)
        // log_message('debug', 'AdminFilter: Usuario autorizado - ID: ' . session()->get('id_usuario') . ', Rol: ' . $rolId);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita lógica después de la respuesta
    }
}
