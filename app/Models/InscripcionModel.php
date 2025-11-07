<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Models;

// Importa la clase base 'Model' de CodeIgniter.
use CodeIgniter\Model;

// Importa el trait para soft delete (borrado lógico).
/** @var \CodeIgniter\Model\SoftDeleteTrait */
use CodeIgniter\Model\SoftDeleteTrait;

/**
 * Modelo para interactuar con la tabla 'Inscripcion'.
 * Define los campos permitidos, reglas de validación y métodos personalizados.
 * Incluye soft delete para marcar registros como borrados sin eliminarlos físicamente.
 */
class InscripcionModel extends Model
{
    // Usa el trait SoftDeleteTrait para habilitar el borrado lógico.
    use SoftDeleteTrait;

    // --- Propiedades de Configuración del Modelo ---

    // Especifica la tabla de la base de datos que este modelo representa.
    protected $table      = 'Inscripcion';
    // Especifica el nombre de la columna que es la clave primaria.
    protected $primaryKey = 'id';
    // Define los campos que se pueden insertar o actualizar masivamente.
    // Nota: No incluyas 'deleted_at' aquí, ya que el trait lo maneja automáticamente.
    protected $allowedFields = ['id', 'estudiante_id', 'materia_id', 'fecha_inscripcion', 'estado_inscripcion', 'observaciones_inscripcion', 'fecha_aprobacion', 'cupo_asignado'];
    // Desactiva los campos de timestamp automáticos ('created_at', 'updated_at').
    protected $useTimestamps = false;
    // Especifica el campo para soft delete (debe coincidir con el nombre en la BD).
    protected $deletedField = 'deleted_at';

    // Define las reglas de validación que se aplicarán antes de guardar o actualizar.
    protected $validationRules = [
        // 'id' no es requerido para la creación.
        'id' => 'permit_empty|integer',
        // 'estudiante_id' es obligatorio y debe existir en la tabla Estudiante.
        'estudiante_id' => 'required|integer|is_not_unique[Estudiante.id]',
        // 'materia_id' es obligatorio y debe existir en la tabla Materia.
        'materia_id' => 'required|integer|is_not_unique[Materia.id]',
        // 'fecha_inscripcion' es obligatoria y debe ser una fecha válida.
        'fecha_inscripcion' => 'required|valid_date',
        // 'estado_inscripcion' es obligatorio y debe ser uno de los valores enumerados.
        'estado_inscripcion' => 'required|in_list[Pendiente,Confirmada,Anulada,Aprobada,Reprobada]',
        // 'observaciones_inscripcion' es opcional, con un máximo de 255 caracteres.
        'observaciones_inscripcion' => 'permit_empty|max_length[255]',
        // 'fecha_aprobacion' es opcional, pero si se proporciona, debe ser una fecha válida.
        'fecha_aprobacion' => 'permit_empty|valid_date',
        // 'cupo_asignado' es opcional, pero si se proporciona, debe ser un entero positivo.
        'cupo_asignado' => 'permit_empty|integer|greater_than[0]',
    ];

    // Define mensajes de error personalizados para las reglas de validación.
    protected $validationMessages = [
        'estudiante_id' => [
            'is_not_unique' => 'El estudiante seleccionado no existe.',
        ],
        'materia_id' => [
            'is_not_unique' => 'La materia seleccionada no existe.',
        ],
        'estado_inscripcion' => [
            'in_list' => 'El estado de inscripción debe ser uno de: Pendiente, Confirmada, Anulada, Aprobada, Reprobada.',
        ],
        'fecha_inscripcion' => [
            'valid_date' => 'La fecha de inscripción debe ser una fecha válida.',
        ],
        'fecha_aprobacion' => [
            'valid_date' => 'La fecha de aprobación debe ser una fecha válida.',
        ],
        'cupo_asignado' => [
            'greater_than' => 'El cupo asignado debe ser mayor a 0.',
        ],
    ];

    // --- Métodos Personalizados ---

    /**
     * Obtiene todas las inscripciones junto con información del estudiante y la materia.
     * Utiliza joins para incluir nombres de estudiante y materia.
     * Nota: Con soft delete, solo se devuelven inscripciones no borradas (deleted_at IS NULL).
     * @return array
     */
    public function getInscripcionesConDetalles()
    {
        return $this->select('Inscripcion.*, Estudiante.nombre_estudiante, Materia.nombre_materia, Materia.codigo_materia')
            ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
            ->join('Materia', 'Materia.id = Inscripcion.materia_id')
            ->findAll();
    }

    /**
     * Obtiene una inscripción específica por ID, junto con detalles del estudiante y materia.
     * Nota: Si la inscripción está borrada lógicamente, devolverá null.
     * @param int $id El ID de la inscripción.
     * @return array|object|null Devuelve la inscripción o null si no se encuentra o está borrada.
     */
    public function getInscripcionConDetalles($id)
    {
        return $this->select('Inscripcion.*, Estudiante.nombre_estudiante, Materia.nombre_materia, Materia.codigo_materia')
            ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
            ->join('Materia', 'Materia.id = Inscripcion.materia_id')
            ->find($id);
    }

    /**
     * Obtiene todas las inscripciones de un estudiante específico.
     * Nota: Solo incluye inscripciones no borradas.
     * @param int $estudianteId El ID del estudiante.
     * @return array Devuelve un array de inscripciones.
     */
    public function getInscripcionesPorEstudiante($estudianteId)
    {
        return $this->select('Inscripcion.*, Materia.nombre_materia, Materia.codigo_materia')
            ->join('Materia', 'Materia.id = Inscripcion.materia_id')
            ->where('Inscripcion.estudiante_id', $estudianteId)
            ->findAll();
    }

    /**
     * Obtiene todas las inscripciones para una materia específica.
     * Nota: Solo incluye inscripciones no borradas.
     * @param int $materiaId El ID de la materia.
     * @return array Devuelve un array de inscripciones.
     */
    public function getInscripcionesPorMateria($materiaId)
    {
        return $this->select('Inscripcion.*, Estudiante.nombre_estudiante')
            ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
            ->where('Inscripcion.materia_id', $materiaId)
            ->findAll();
    }

    /**
     * Obtiene inscripciones por estado (e.g., 'Confirmada', 'Aprobada').
     * Nota: Solo incluye inscripciones no borradas.
     * @param string $estado El estado de la inscripción.
     * @return array Devuelve un array de inscripciones.
     */
    public function getInscripcionesPorEstado($estado)
    {
        return $this->select('Inscripcion.*, Estudiante.nombre_estudiante, Materia.nombre_materia')
            ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
            ->join('Materia', 'Materia.id = Inscripcion.materia_id')
            ->where('Inscripcion.estado_inscripcion', $estado)
            ->findAll();
    }

    /**
     * Verifica si un estudiante ya está inscrito en una materia específica.
     * Nota: Solo considera inscripciones no borradas.
     * @param int $estudianteId El ID del estudiante.
     * @param int $materiaId El ID de la materia.
     * @return bool True si ya está inscrito, false en caso contrario.
     */
    public function estudianteInscritoEnMateria($estudianteId, $materiaId)
    {
        $count = $this->where('estudiante_id', $estudianteId)
            ->where('materia_id', $materiaId)
            ->countAllResults();
        return $count > 0;
    }
}