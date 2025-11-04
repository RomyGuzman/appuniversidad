<?php

namespace App\Controllers;

use App\Models\ConsultaAdminModel;
use CodeIgniter\Controller;

class Consultas extends Controller
{
    public function enviar()
    {
        $validation = \Config\Services::validation();

        // Validación base - requerir email, asunto, mensaje y tipo_usuario
        $rules = [
            'email'   => 'required|valid_email',
            'asunto'  => 'required|max_length[80]',
            'mensaje' => 'required|max_length[300]',
            'tipo_usuario' => 'required|in_list[estudiante,profesor]',
        ];

        $validation->setRules($rules);

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Datos inválidos.',
                    'errors'  => $validation->getErrors()
                ]);
            } else {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        }

        $model = new ConsultaAdminModel();

        $email = $this->request->getPost('email');

        $data = [
            'email_usuario' => $email,
            'asunto'        => $this->request->getPost('asunto'),
            'mensaje'       => $this->request->getPost('mensaje'),
            'estado'        => 'pendiente',
        ];

        // Guardar usuario_id y rol_id basado en tipo_usuario
        $tipo_usuario = $this->request->getPost('tipo_usuario');
        if ($tipo_usuario === 'estudiante') {
            $data['usuario_id'] = 3; // Usuario por defecto para estudiantes
            $data['rol_id'] = 3; // Rol por defecto para estudiantes
        } elseif ($tipo_usuario === 'profesor') {
            $data['usuario_id'] = $this->request->getPost('usuario_id') ?: 2; // Usuario por defecto para profesores si no se proporciona
            $data['rol_id'] = 2; // Rol por defecto para profesores
        }

        if ($model->insert($data)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'El administrador se contactará al e-mail proporcionado',
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return redirect()->back()->with('success', 'Consulta enviada exitosamente. El administrador se contactará pronto.');
            }
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al guardar la consulta',
                    'csrf_token' => csrf_hash()
                ]);
            } else {
                return redirect()->back()->with('error', 'Error al guardar la consulta.');
            }
        }
    }
}

