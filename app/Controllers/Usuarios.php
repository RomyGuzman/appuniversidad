<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\RolModel;

class Usuarios extends BaseController
{
    protected $usuarioModel;
    protected $rolModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->rolModel = new RolModel();
    }

    public function index()
    {
        $data['usuarios'] = $this->usuarioModel->findAll();
        $data['roles'] = $this->rolModel->findAll();
        return view('administrador/usuarios', $data);
    }

    public function registrar()
    {
        $data = [
            'usuario' => $this->request->getPost('usuario'),
          // Usando md5() para ser consistente con el sistema de login.
            'password' => md5($this->request->getPost('password')),
            'rol_id' => $this->request->getPost('rol_id'),
            'activo' => $this->request->getPost('activo') ? 1 : 0,
        ];

        // Verificar si el usuario ya existe
        $existingUser = $this->usuarioModel->where('usuario', $data['usuario'])->first();
        if ($existingUser) {
            return redirect()->back()->withInput()->with('errors', ['usuario' => 'El nombre de usuario ya existe.']);
        }

        if ($this->usuarioModel->insert($data)) {
            // SOLUCIÓN DEFINITIVA: Limpiamos explícitamente los datos del formulario de la sesión
            // antes de redirigir. Esto garantiza que el formulario siempre estará vacío.
            session()->remove('_ci_old_input');
            return redirect()->to('/administrador/usuarios')->with('success', 'Usuario registrado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    public function edit($id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuario no encontrado');
        }
        return $this->response->setJSON($usuario);
    }

    public function update($id)
    {
        $data = [
            'usuario' => $this->request->getPost('usuario'),
            'rol_id' => $this->request->getPost('rol_id'),
            'activo' => $this->request->getPost('activo') ? 1 : 0,
        ];

        // Obtener el usuario actual
        $currentUser = $this->usuarioModel->find($id);
        if (!$currentUser) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        // Verificar si el nombre de usuario ha cambiado
        if ($currentUser['usuario'] != $data['usuario']) {
            // Si cambió, verificar si el nuevo nombre de usuario ya existe
            $existingUser = $this->usuarioModel->where('usuario', $data['usuario'])->first();
            if ($existingUser) {
                return redirect()->back()->withInput()->with('errors', ['usuario' => 'El nombre de usuario ya existe.']);
            }
        }

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            // Si se actualiza la contraseña, también usamos md5().
            $data['password'] = md5($password);
        }

        if ($this->usuarioModel->update($id, $data)) {
            return redirect()->to('/administrador/usuarios')->with('success', 'Usuario actualizado exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->usuarioModel->errors());
        }
    }

    public function delete($id)
    {
        if ($this->usuarioModel->delete($id)) {
            return redirect()->to('/administrador/usuarios')->with('success', 'Usuario eliminado exitosamente.');
        } else {
            return redirect()->to('/administrador/usuarios')->with('error', 'Error al eliminar el usuario.');
        }
    }

    public function search($id)
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON(['error' => 'Usuario no encontrado']);
        }
        return $this->response->setJSON($usuario);
    }
}
