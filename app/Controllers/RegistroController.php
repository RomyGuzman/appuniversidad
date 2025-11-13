<?php

namespace App\Controllers;

use App\Models\RegistroEstudianteModel;
use App\Models\CarreraModel;
use App\Models\UsuarioModel;
use CodeIgniter\Controller;
use CodeIgniter\Database\Exceptions\DatabaseException;

class RegistroController extends Controller
{
    public function index()
    {
        $carreraModel = new CarreraModel();
        $data['carreras'] = $carreraModel->orderBy('nombre_carrera', 'ASC')->findAll();
        $data['hide_about'] = true;

        return view('registro', $data);
    }

    public function registrar()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'dni' => 'required|exact_length[8]',
            'nombre_estudiante' => 'required|max_length[80]',
            'fecha_nacimiento' => 'required|valid_date',
            'email' => 'required|valid_email',
            'carrera_id' => 'required|is_not_unique[Carrera.id]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $registroEstudianteModel = new RegistroEstudianteModel();
        $usuarioModel = new UsuarioModel();

        $dni  = $this->request->getPost('dni');
        $email = $this->request->getPost('email');

        $dataEstudiante = [
            'dni'              => $dni,
            'nombre_estudiante'=> $this->request->getPost('nombre_estudiante'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'edad'             => $this->request->getPost('edad'),
            'email'            => $email,
            'carrera_id'       => $this->request->getPost('carrera_id'),
        ];

        // ✅ Guardar estudiante
        try {
            if (!$registroEstudianteModel->insert($dataEstudiante)) {
                return redirect()->back()->withInput()->with('errors', $registroEstudianteModel->errors());
            }
        } catch (DatabaseException $e) {
            // Verificar si es un error de entrada duplicada
            if ($e->getCode() == 1062) {
                return redirect()->back()->withInput()->with('dni_duplicado', true);
            } else {
                // Para otros errores de base de datos, mostrar el error genérico
                return redirect()->back()->withInput()->with('errors', ['Error en la base de datos: ' . $e->getMessage()]);
            }
        }

        // ✅ Crear el usuario automáticamente
        $dataUsuario = [
            'usuario'  => $email,
            'password' => md5($dni),
            'rol_id'   => 3,
            'activo'   => 1
        ];

        $usuarioModel->insert($dataUsuario);

        return redirect()->to(base_url('registro'))
            ->with('registro_exitoso', true)
            ->with('email', $email)
            ->with('dni', $dni);
    }
}
