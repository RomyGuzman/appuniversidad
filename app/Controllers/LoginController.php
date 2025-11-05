<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function autenticar()
    {
        $login_identifier = $this->request->getPost('login_identifier');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('usuario', $login_identifier)->first();

        if ($usuario && md5($password) === $usuario['password']) {
            session()->set([
                'usuario' => $usuario['usuario'],
                'id_usuario' => $usuario['id'],
                'rol_id' => $usuario['rol_id']
            ]);

            // Redirigir según el rol
            if ($usuario['rol_id'] == 1 || $usuario['rol_id'] == 4) { // Admin
                return redirect()->to(base_url('administrador/usuarios'));
            } elseif ($usuario['rol_id'] == 2) { // Profesor
                return redirect()->to(base_url('profesores/dashboard'));
            } elseif ($usuario['rol_id'] == 3) { // Estudiante
                return redirect()->to(base_url('estudiantes/dashboard'));
            } else {
                return redirect()->to(base_url());
            }
        } else {
            return redirect()->back()->with('error', 'Usuario o contraseña incorrectos.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url());
    }
}
