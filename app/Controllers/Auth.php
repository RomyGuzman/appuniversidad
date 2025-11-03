<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use App\Models\EstudianteModel;
use App\Models\ProfesorModel;
use App\Models\RolModel;

class Auth extends BaseController
{
    /**
     * Muestra el formulario de login.
     */
    public function login()
    {
        // Si el usuario ya está logueado, lo redirigimos a su dashboard.
        if (session()->get('isLoggedIn')) {
            return redirect()->to($this->getDashboardRedirect(session()->get('rol_id')));
        }
        return view('login'); // Asegúrate de tener una vista en app/Views/login.php
    }

    /**
     * Procesa el intento de inicio de sesión.
     */
    public function attemptLogin()
    {
        // 1. Validar que los campos no estén vacíos
        $rules = [
            'login_identifier' => 'required', // El campo del form se llamará 'login_identifier'
            'password'         => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/login')->with('errors', $this->validator->getErrors());
        }

        $identifier = $this->request->getPost('login_identifier');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $estudianteModel = new EstudianteModel();

        // 2. Lógica de autenticación simplificada y correcta
        // Buscamos directamente por el nombre de usuario ('admin', 'profesor1', 'alumno1', etc.)
        $usuario = $usuarioModel->where('usuario', $identifier)->first();

        // 3. Verificar contraseña y crear sesión
        // CORRECCIÓN PRECISA: Se utiliza md5() para comparar la contraseña con el hash MD5
        // en la base de datos, como solicitado.
        if ($usuario && md5($password) === $usuario['password']) {
            // ¡Login exitoso!
            $this->setUserSession($usuario);

            // Actualizar fecha de último ingreso
            $usuarioModel->update($usuario['id'], ['fecha_ultimo_ingreso' => date('Y-m-d H:i:s')]);

            // Redirigir al dashboard correspondiente
            return redirect()->to($this->getDashboardRedirect($usuario['rol_id']));
        }


        // 4. Si todo falla, login incorrecto
        return redirect()->to('/login')->with('error', 'Usuario o contraseña incorrectos.');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Has cerrado sesión correctamente.');
    }

    /**
     * Establece los datos de la sesión del usuario.
     */
    private function setUserSession($usuario)
    {
        $rolModel = new RolModel();
        $rol = $rolModel->find($usuario['rol_id']);

        $data = [
            'id_usuario' => $usuario['id'],
            'usuario'    => $usuario['usuario'],
            'rol_id'     => $usuario['rol_id'],
            'nombre_rol' => $rol ? $rol['nombre_rol'] : 'Desconocido',
            'isLoggedIn' => true,
        ];

        session()->set($data);
    }

    /**
     * Devuelve la URL del dashboard según el rol del usuario.
     */
    private function getDashboardRedirect($rol_id)
    {
        switch ($rol_id) {
            case 1: // admin
            case 4: // Superadmin
                return 'administrador/usuarios';
            case 2: // profesor
                return 'profesores/dashboard';
            case 3: // alumno
                return 'estudiantes/dashboard';
            default:
                return '/';
        }
    }
}