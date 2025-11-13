<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriaModel extends Model
{
    protected $table      = 'materia';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_materia', 'codigo_materia', 'carrera_id'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre_materia' => 'required|min_length[2]|max_length[120]',
        'codigo_materia' => 'required|min_length[2]|max_length[20]|is_unique[materia.codigo_materia,id,{id}]',
        'carrera_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'codigo_materia' => [
            'is_unique' => 'El código de materia ya existe.',
        ],
        'carrera_id' => [
            'integer' => 'La carrera debe ser un número entero.',
        ],
    ];

    /**
     * Obtiene los materiales de estudio de una materia específica.
     * @param int $id_mat El ID de la materia.
     * @return array Devuelve un array de materiales.
     */
    public function getMateriales($id_mat)
    {
        return $this->db->table('Material')
            ->where('materia_id', $id_mat)
            ->get()
            ->getResultArray();
    }
}
