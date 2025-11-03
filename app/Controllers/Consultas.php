<?php

namespace App\Controllers;

use App\Models\ConsultaAdminModel;
use CodeIgniter\Controller;

class Consultas extends Controller
{
    public function enviar()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'   => 'required|valid_email',
            'asunto'  => 'required|max_length[80]',
            'mensaje' => 'required|max_length[300]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Datos inválidos.',
                'errors'  => $validation->getErrors()
            ]);
        }

        $model = new ConsultaAdminModel();

        $data = [
            'email_usuario' => $this->request->getPost('email'),
            'asunto'        => $this->request->getPost('asunto'),
            'mensaje'       => $this->request->getPost('mensaje'),
            'estado'        => 'pendiente'
        ];

        if ($model->insert($data)) {
           return $this->response->setJSON([
    'success' => true,
    'message' => 'El administrador se contactará al e-mail proporcionado',
    'csrf_token' => csrf_hash()
            ]);
        
    return $this->response->setJSON([
    'success' => false,
    'message' => 'Error al guardar la consulta',
    'csrf_token' => csrf_hash()
]);
}
    }
}

