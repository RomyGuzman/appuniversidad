<?php

namespace App\Controllers;

use App\Models\CarreraModel;
use App\Models\EstudianteModel;
use App\Models\ModalidadModel;
use App\Models\CategoriaModel;

class RegistroController extends BaseController
{
    public function index()
    {
        $carreraModel = new CarreraModel();
        $data['carreras'] = $carreraModel->findAll();
        return view('Registro', $data);
    }

    public function registrarEstudiante()
    {
        $estudianteModel = new EstudianteModel();
        $data = [
            'dni' => $this->request->getPost('dni'),
            'nombre_estudiante' => $this->request->getPost('nombre_estudiante'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'email' => $this->request->getPost('email'),
            'carrera_id' => $this->request->getPost('carrera_id'),
        ];

        if ($estudianteModel->save($data)) {
            return redirect()->to('/registro')->with('success', '¡Te has registrado exitosamente!');
        } else {
            return redirect()->to('/registro')->withInput()->with('errors', $estudianteModel->errors());
        }
    }

    public function getModalidades($carreraId) {
        $modalidadModel = new ModalidadModel();
        return $this->response->setJSON($modalidadModel->where('carrera_id', $carreraId)->findAll());
    }

    public function getCategorias($carreraId) {
        $categoriaModel = new CategoriaModel();
        return $this->response->setJSON($categoriaModel->where('carrera_id', $carreraId)->findAll());
    }
}