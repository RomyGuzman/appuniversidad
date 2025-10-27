<?php

namespace App\Controllers;

use App\Models\MateriaModel;
use App\Models\CarreraModel;

class Materias extends BaseController
{
    protected $materiaModel;
    protected $carreraModel;

    public function __construct()
    {
        $this->materiaModel = new MateriaModel();
        $this->carreraModel = new CarreraModel();
    }

    public function index()
    {
        try {
            $perPage = 10; // Número de registros por página
            $page = $this->request->getVar('page') ?? 1;
            $carreraId = $this->request->getVar('carrera_id');

            if ($carreraId) {
                $this->materiaModel->where('carrera_id', $carreraId);
            }

            $data['materias'] = $this->materiaModel->paginate($perPage, 'default', $page);
            $data['pager'] = $this->materiaModel->pager;

            $data['carreras'] = $this->carreraModel->findAll();
            $data['selectedCarrera'] = $carreraId;
        } catch (\Exception $e) {
            $data['materias'] = [];
            $data['carreras'] = [];
            $data['selectedCarrera'] = null;
            $data['error'] = 'Error al cargar los datos: ' . $e->getMessage();
        }
        return view('administrador/materias', $data);
    }

    public function registrar()
    {
        $nombreMateria = $this->request->getPost('nombre_materia');
        $codigoGenerado = $this->generarCodigoMateria($nombreMateria);

        $data = [
            'nombre_materia' => $nombreMateria,
            'codigo_materia' => $codigoGenerado,
            'carrera_id' => $this->request->getPost('carrera_id'),
        ];

        if ($this->materiaModel->insert($data)) {
            return redirect()->to('/administrador/materias')->with('success', "Materia registrada exitosamente. Código generado: <strong>{$codigoGenerado}</strong>");
        } else {
            return redirect()->back()->withInput()->with('errors', $this->materiaModel->errors());
        }
    }

    public function edit($id)
    {
        $materia = $this->materiaModel->find($id);
        if (!$materia) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Materia no encontrada');
        }
        return $this->response->setJSON($materia);
    }

    public function update($id)
    {
        $data = [
            'nombre_materia' => $this->request->getPost('nombre_materia'),
            'codigo_materia' => $this->request->getPost('codigo_materia'),
            'carrera_id' => $this->request->getPost('carrera_id'),
        ];

        if ($this->materiaModel->update($id, $data)) {
            return redirect()->to('/administrador/materias')->with('success', 'Materia actualizada exitosamente.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->materiaModel->errors());
        }
    }

    public function delete($id)
    {
        if ($this->materiaModel->delete($id)) {
            return redirect()->to('/administrador/materias')->with('success', 'Materia eliminada exitosamente.');
        } else {
            return redirect()->to('/administrador/materias')->with('error', 'Error al eliminar la materia.');
        }
    }

    public function search($id)
    {
        $materia = $this->materiaModel->find($id);
        if (!$materia) {
            return $this->response->setJSON(['error' => 'Materia no encontrada']);
        }
        return $this->response->setJSON($materia);
    }

    public function searchCarrera()
    {
        $searchTerm = $this->request->getPost('search_carrera');
        if (!$searchTerm) {
            return $this->response->setJSON(['error' => 'Término de búsqueda requerido']);
        }

        $carreras = $this->carreraModel->like('ncar', $searchTerm)->findAll();
        return $this->response->setJSON($carreras);
    }

    /**
     * Genera un código único para una materia basado en su nombre.
     * Ejemplo: "Programación I" -> "PROG-1", "PROG-2", etc.
     * @param string $nombreMateria El nombre de la materia.
     * @return string El código generado.
     */
    public function generarCodigo($nombreMateria = '')
    {
        if (empty($nombreMateria)) {
            return $this->response->setJSON(['error' => 'Nombre de materia requerido']);
        }

        $codigo = $this->generarCodigoMateria($nombreMateria);
        return $this->response->setJSON(['codigo' => $codigo]);
    }

    /**
     * Genera un código único para una materia basado en su nombre.
     * Ejemplo: "Programación I" -> "PROG-1", "PROG-2", etc.
     * @param string $nombreMateria El nombre de la materia.
     * @return string El código generado.
     */
    private function generarCodigoMateria(string $nombreMateria): string
    {
        // Paso 1: Crear un acrónimo a partir de las iniciales del nombre de la materia.
        $palabras = explode(' ', $nombreMateria);
        $acronimo = '';
        // Limita a un máximo de 4 iniciales para evitar códigos demasiado largos.
        $palabras = array_slice($palabras, 0, 4);

        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                // Concatena la primera letra de cada palabra, en mayúsculas.
                $acronimo .= strtoupper(substr($palabra, 0, 1));
            }
        }

        // Paso 2: Buscar en la base de datos cuántas materias ya existen con ese mismo acrónimo.
        // Por ejemplo, si el acrónimo es "PROG", busca códigos como "PROG-1", "PROG-2", etc.
        $existentes = $this->materiaModel->like('codigo_materia', $acronimo . '-', 'after')->countAllResults();

        // Paso 3: Devolver el nuevo código con el número secuencial siguiente.
        $siguienteNumero = $existentes + 1;
        return $acronimo . '-' . $siguienteNumero;
    }
}
