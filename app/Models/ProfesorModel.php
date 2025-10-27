<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Models;
// Importa la clase base 'Model' de CodeIgniter.
use CodeIgniter\Model;
/**
 * Modelo para interactuar con la tabla 'Profesor'.
 * Define los campos permitidos, reglas de validación y métodos personalizados.
 */
class ProfesorModel extends Model
{
    // --- Propiedades de Configuración del Modelo ---

    // Especifica la tabla de la base de datos que este modelo representa.
    protected $table      = 'Profesor';
    // Especifica el nombre de la columna que es la clave primaria.
    protected $primaryKey = 'id';
    // Define los campos que se pueden insertar o actualizar masivamente.
    protected $allowedFields = ['id', 'legajo', 'nombre_profesor', 'carrera_id'];
    // Desactiva los campos de timestamp automáticos ('created_at', 'updated_at').
    protected $useTimestamps = false;
    // Define el tipo de retorno como array para compatibilidad con la vista.
    protected $returnType = 'array';

    // Define las reglas de validación que se aplicarán antes de guardar o actualizar.
    protected $validationRules = [
        // 'id' no es requerido para la creación.
        'id' => 'permit_empty|integer', // Cambiado a permit_empty para permitir la creación
        // 'legajo' es obligatorio, debe ser un número entero único.
        'legajo'   => 'required|integer|is_unique[Profesor.legajo,id,{id}]',
        // 'nombre_profesor' es obligatorio, con una longitud entre 2 y 80 caracteres.
        'nombre_profesor'  => 'required|min_length[2]|max_length[80]',
    ];
    // Define mensajes de error personalizados para las reglas de validación.
    protected $validationMessages = [
        'legajo' => [
            'integer'=>'El legajo debe ser un número entero.',
            'is_unique'=>'El legajo ya existe.'
        ],
        'nombre_profesor' => [
            'min_length' => 'El nombre debe tener al menos 2 caracteres.',
            'max_length' => 'El nombre no puede exceder 80 caracteres.'
        ],
    ];

    // --- Métodos Personalizados ---

    /**
     * Obtiene todos los profesores junto con el nombre de su carrera.
     * Utiliza un 'left join' para asegurar que se muestren todos los profesores,
     * incluso aquellos que no están asignados a ninguna carrera.
     * @return array
     */
    public function getProfesores()
    {
        // Construye una consulta SELECT que une la tabla 'Profesor' con 'Carrera'.
        return $this->select('Profesor.*, Carrera.nombre_carrera')
            ->join('Carrera', 'Carrera.id = Profesor.carrera_id', 'left') // 'left' join para incluir profesores sin carrera.
            ->findAll(); // Ejecuta la consulta y devuelve todos los resultados.
    }

    /**
     * Obtiene un profesor específico por ID.
     * @param int $id El ID del profesor.
     * @return array|object|null Devuelve el profesor o null si no se encuentra.
     */
    public function getProfesor($id)
    {
        // Construye la consulta para un solo registro.
        return $this->find($id); // Ejecuta la consulta y devuelve el registro que coincide con el ID.
    }

    /**
     * Obtiene un profesor específico por legajo.
     * @param int $legajo El legajo del profesor.
     * @return array|object|null Devuelve el profesor o null si no se encuentra.
     */
    public function getProfesorByLegajo($legajo)
    {
        // Construye la consulta para buscar por legajo.
        return $this->where('legajo', $legajo)->first(); // Ejecuta la consulta y devuelve el primer registro que coincide.
    }

    /**
     * Obtiene un profesor específico por ID, junto con el nombre de su carrera.
     * @param int $id El ID del profesor.
     * @return array|object|null Devuelve el profesor o null si no se encuentra.
     */
    public function getProfesorConCarrera($id)
    {
        // Construye la consulta para un solo registro con join a Carrera.
        return $this->select('Profesor.*, Carrera.nombre_carrera')
            ->join('Carrera', 'Carrera.id = Profesor.carrera_id', 'left')
            ->find($id); // Ejecuta la consulta y devuelve el registro que coincide con el ID.
    }

    /**
     * Obtiene las materias dictadas por un profesor específico.
     * @param int $id_prof El ID del profesor.
     * @return array Devuelve un array de materias con información.
     */
    public function getMateriasDictadas($id_prof)
    {
        return $this->db->table('Profesor_Materia')
            ->select('Materia.nombre_materia, Materia.codigo_materia, Materia.id, Materia.carrera_id, Carrera.nombre_carrera')
            ->join('Materia', 'Materia.id = Profesor_Materia.materia_id')
            ->join('Carrera', 'Carrera.id = Materia.carrera_id')
            ->where('Profesor_Materia.profesor_id', $id_prof)
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene estadísticas de un profesor específico.
     * @param int $id_prof El ID del profesor.
     * @return array Devuelve un array con estadísticas.
     */
    public function getEstadisticas($id_prof)
    {
        $materias = $this->getMateriasDictadas($id_prof);
        $total_materias = count($materias);

        $total_estudiantes = 0;
        foreach ($materias as $materia) {
            $estudiantes = $this->db->table('Inscripcion')
                ->where('materia_id', $materia['id'])
                ->countAllResults();
            $total_estudiantes += $estudiantes;
        }

        // Promedio de calificaciones dadas (opcional, si hay notas)
        $notas = $this->db->table('Nota')
            ->select('AVG(calificacion) as promedio')
            ->join('Materia', 'Materia.id = Nota.materia_id')
            ->join('Profesor_Materia', 'Profesor_Materia.materia_id = Materia.id')
            ->where('Profesor_Materia.profesor_id', $id_prof)
            ->get()
            ->getRowArray();

        $promedio_calificaciones = $notas['promedio'] ?? 0;

        return [
            'total_materias' => $total_materias,
            'total_estudiantes' => $total_estudiantes,
            'promedio_calificaciones' => round($promedio_calificaciones, 2)
        ];
    }

    /**
     * Obtiene los estudiantes inscritos en las materias dictadas por un profesor.
     * @param int $id_prof El ID del profesor.
     * @return array Devuelve un array agrupado por materia.
     */
    public function getEstudiantesPorMateria($id_prof)
    {
        $materias = $this->getMateriasDictadas($id_prof);
        $estudiantes_por_materia = [];

        foreach ($materias as $materia) {
            $estudiantes = $this->db->table('Inscripcion')
                ->select('Estudiante.id, Estudiante.nombre_estudiante, Estudiante.dni, Estudiante.email, Inscripcion.fecha_inscripcion, Inscripcion.estado_inscripcion')
                ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
                ->where('Inscripcion.materia_id', $materia['id'])
                ->get()
                ->getResultArray();
            $estudiantes_por_materia[$materia['id']] = $estudiantes;
        }

        return $estudiantes_por_materia;
    }

    /**
     * Obtiene las carreras asignadas a un profesor basado en las materias que dicta.
     * @param int $id_prof El ID del profesor.
     * @return array Devuelve un array de carreras únicas con información completa.
     */
    public function getCarrerasDelProfesor($id_prof)
    {
        return $this->db->table('Profesor_Materia')
            ->select('carrera.id, carrera.nombre_carrera, carrera.codigo_carrera, categoria.nombre_categoria, modalidad.nombre_modalidad')
            ->join('materia', 'materia.id = Profesor_Materia.materia_id')
            ->join('carrera', 'carrera.id = materia.carrera_id')
            ->join('categoria', 'categoria.id = carrera.id_cat', 'left')
            ->join('modalidad', 'modalidad.id = carrera.id_mod', 'left')
            ->where('Profesor_Materia.profesor_id', $id_prof)
            ->groupBy('Carrera.id')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene los estudiantes inscritos en las materias de una carrera específica dictadas por un profesor.
     * @param int $carrera_id El ID de la carrera.
     * @param int $id_prof El ID del profesor.
     * @return array Devuelve un array de estudiantes.
     */
    public function getEstudiantesPorCarrera($carrera_id, $id_prof)
    {
        return $this->db->table('Inscripcion')
            ->select('Estudiante.id, Estudiante.nombre_estudiante, Estudiante.dni, Inscripcion.fecha_inscripcion, Inscripcion.estado_inscripcion')
            ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
            ->join('Materia', 'Materia.id = Inscripcion.materia_id')
            ->join('Profesor_Materia', 'Profesor_Materia.materia_id = Materia.id')
            ->where('Materia.carrera_id', $carrera_id)
            ->where('Profesor_Materia.profesor_id', $id_prof)
            ->groupBy('Estudiante.id')
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene los estudiantes inscritos en una materia específica.
     * @param int $materia_id El ID de la materia.
     * @return array Devuelve un array de estudiantes.
     */
    public function getEstudiantesPorMateriaEspecifica($materia_id)
    {
        return $this->db->table('Inscripcion')
            ->select('Estudiante.id, Estudiante.nombre_estudiante, Estudiante.dni')
            ->join('Estudiante', 'Estudiante.id = Inscripcion.estudiante_id')
            ->where('Inscripcion.materia_id', $materia_id)
            ->get()
            ->getResultArray();
    }

    /**
     * Obtiene el detalle de asistencia por materia para un profesor.
     * @param int $materia_id El ID de la materia.
     * @return array Devuelve un array de asistencias.
     */
    public function getDetalleAsistenciaPorMateria($materia_id)
    {
        return $this->db->table('Asistencia')
            ->select('Asistencia.fecha, Asistencia.estado, Estudiante.nombre_estudiante')
            ->join('Estudiante', 'Estudiante.id = Asistencia.estudiante_id')
            ->where('Asistencia.materia_id', $materia_id)
            ->orderBy('Asistencia.fecha', 'DESC')
            ->get()
            ->getResultArray();
    }
}
