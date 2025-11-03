<?php

namespace App\Controllers;

use App\Models\RegistroEstudianteModel;
use App\Models\CarreraModel;
use App\Models\ModalidadModel;
use App\Models\CategoriaModel;
use CodeIgniter\Controller;

class RegistroController extends Controller
{
    public function index()
    {
        // Cargar carreras para el dropdown
        $carreraModel = new CarreraModel();
        $data['carreras'] = $carreraModel->findAll();
        $data['show_header'] = false;  // Oculta el header en esta vista

        return view('registro', $data);
    }

    // ... (resto del código permanece igual)

    public function registrar()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'dni' => 'required|exact_length[8]|is_unique[Estudiante.dni]',
            'nombre_estudiante' => 'required|max_length[80]',
            'fecha_nacimiento' => 'required|valid_date',
            'email' => 'required|valid_email|is_unique[Estudiante.email]',
            'carrera_id' => 'required|is_not_unique[Carrera.id]',
            'modalidad_id' => 'required|is_not_unique[Modalidad.id]',
            'categoria_id' => 'required|is_not_unique[Categoria.id]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'dni' => $this->request->getPost('dni'),
            'nombre_estudiante' => $this->request->getPost('nombre_estudiante'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'email' => $this->request->getPost('email'),
            'carrera_id' => $this->request->getPost('carrera_id')
        ];

        $registroEstudianteModel = new RegistroEstudianteModel();
        try {
            $registroEstudianteModel->registrarEstudiante($data);
            return redirect()->to('/login')->with('success', 'Registro exitoso. Inicia sesión con tu email y DNI.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['Error al registrar: ' . $e->getMessage()]);
        }
    }

    // Métodos para AJAX: cargar modalidades y categorías por carrera
    public function getModalidades($carreraId)
    {
        $modalidadModel = new ModalidadModel();
        $modalidades = $modalidadModel->where('carrera_id', $carreraId)->findAll();
        return $this->response->setJSON($modalidades);
    }

    public function getCategorias($carreraId)
    {
        $categoriaModel = new CategoriaModel();
        $categorias = $categoriaModel->where('carrera_id', $carreraId)->findAll();
        return $this->response->setJSON($categorias);
    }
}