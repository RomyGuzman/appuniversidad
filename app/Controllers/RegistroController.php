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
        $data['carreras'] = $carreraModel->orderBy('nombre_carrera', 'ASC')->findAll(); // Usamos findAll() para obtener todas las carreras

        // Cargar todas las modalidades
        $modalidadModel = new ModalidadModel();
        $data['modalidades'] = $modalidadModel->orderBy('nombre_modalidad', 'ASC')->findAll();

        // Cargar todas las categorías
        $categoriaModel = new CategoriaModel();
        $data['categorias'] = $categoriaModel->orderBy('nombre_categoria', 'ASC')->findAll();

        // Ocultar la sección "Quiénes Somos" en esta vista
        $data['hide_about'] = true;

        // Pasamos los datos a la vista 'registro.php'
        return view('registro', $data); // El nombre de la vista es 'registro', no 'Registro'
    }

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
            'carrera_id' => $this->request->getPost('carrera_id'),
            'modalidad_id' => $this->request->getPost('modalidad_id'),
            'categoria_id' => $this->request->getPost('categoria_id')
        ];

        $registroEstudianteModel = new RegistroEstudianteModel();
        try {
            $registroEstudianteModel->registrarEstudiante($data);
            return redirect()->back()->with('registro_exitoso', true)->with('email', $data['email'])->with('dni', $data['dni']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['Error al registrar: ' . $e->getMessage()]);
        }
    }

    // Métodos para AJAX: cargar modalidades y categorías por carrera
    public function getModalidades($carreraId)
    {
        $modalidadModel = new ModalidadModel();
        // Aseguramos que el ID sea un entero válido
        if (!is_numeric($carreraId) || $carreraId <= 0) {
            return $this->response->setJSON([]);
        }
        $modalidades = $modalidadModel->where('carrera_id', (int)$carreraId)->findAll();
        return $this->response->setJSON($modalidades);
    }

    public function getCategorias($carreraId)
    {
        $categoriaModel = new CategoriaModel();
        // Aseguramos que el ID sea un entero válido
        if (!is_numeric($carreraId) || $carreraId <= 0) {
            return $this->response->setJSON([]);
        }
        $categorias = $categoriaModel->where('carrera_id', (int)$carreraId)->findAll();
        return $this->response->setJSON($categorias);
    }
}