<?php

namespace App\Models;

use CodeIgniter\Model;

class InscripcionModel extends Model
{
    protected $table = 'inscripcion';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'estudiante_id',
        'materia_id',
        'fecha_inscripcion',
        'estado_inscripcion',
        'observaciones_inscripcion'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'estudiante_id' => 'required|integer',
        'materia_id' => 'required|integer',
        'fecha_inscripcion' => 'required|valid_date[Y-m-d]',
        'estado_inscripcion' => 'required|in_list[Pendiente,Confirmada,Anulada,Aprobada,Reprobada]',
        'observaciones_inscripcion' => 'permit_empty|string|max_length[255]'
    ];

    protected $validationMessages = [
        'estudiante_id' => [
            'required' => 'El ID del estudiante es obligatorio',
            'integer' => 'El ID del estudiante debe ser un número entero'
        ],
        'materia_id' => [
            'required' => 'El ID de la materia es obligatorio',
            'integer' => 'El ID de la materia debe ser un número entero'
        ],
        'fecha_inscripcion' => [
            'required' => 'La fecha de inscripción es obligatoria',
            'valid_date' => 'La fecha de inscripción debe tener un formato válido (Y-m-d)'
        ],
        'estado_inscripcion' => [
            'required' => 'El estado de la inscripción es obligatorio',
            'in_list' => 'El estado debe ser uno de los siguientes: Pendiente, Confirmada, Anulada, Aprobada, Reprobada'
        ],
        'observaciones_inscripcion' => [
            'string' => 'Las observaciones deben ser texto',
            'max_length' => 'Las observaciones no pueden exceder los 255 caracteres'
        ]
    ];

    /**
     * Obtiene la última inscripción de un estudiante en una materia específica
     */
    public function getUltimaInscripcion($estudianteId, $materiaId)
    {
        return $this->where('estudiante_id', $estudianteId)
                   ->where('materia_id', $materiaId)
                   ->orderBy('fecha_inscripcion', 'DESC')
                   ->first();
    }

    /**
     * Obtiene todas las inscripciones activas de un estudiante
     */
    public function getInscripcionesActivas($estudianteId)
    {
        return $this->where('estudiante_id', $estudianteId)
                   ->whereIn('estado_inscripcion', ['Pendiente', 'Confirmada', 'Aprobada'])
                   ->findAll();
    }

    /**
     * Obtiene todos los estudiantes inscritos en una materia específica
     */
    public function getEstudiantesPorMateria($materiaId)
    {
        return $this->select('inscripcion.*, estudiante.nombre_estudiante, estudiante.email')
                   ->join('estudiante', 'estudiante.id = inscripcion.estudiante_id')
                   ->where('inscripcion.materia_id', $materiaId)
                   ->whereIn('inscripcion.estado_inscripcion', ['Pendiente', 'Confirmada', 'Aprobada'])
                   ->findAll();
    }
}
