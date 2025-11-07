<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Models;

// Importa la clase base 'Model' de CodeIgniter.
use CodeIgniter\Model;

// Importa el trait para soft delete (borrado lógico).
use CodeIgniter\Model\SoftDeleteTrait;

/**
 * Modelo para interactuar con la tabla 'Estudiante'.
 * Define los campos permitidos, reglas de validación y métodos personalizados.
 * Incluye soft delete para marcar registros como borrados sin eliminarlos físicamente.
 */
class EstudianteModel extends Model
{
    // Usa el trait SoftDeleteTrait para habilitar el borrado lógico.
    use SoftDeleteTrait;

    // --- Propiedades de Configuración del Modelo ---

    // Especifica la tabla de la base de datos que este modelo representa.
    protected $table      = 'Estudiante';
    // Especifica el nombre de la columna que es la clave primaria.
    protected $primaryKey = 'id';
    // Define los campos que se pueden insertar o actualizar masivamente.
    // Nota: No incluyas 'deleted_at' aquí, ya que el trait lo maneja automáticamente.
    protected $allowedFields = ['id', 'dni','nombre_estudiante','fecha_nacimiento','edad','email','carrera_id'];
    // Desactiva los campos de timestamp automáticos ('created_at', 'updated_at').
    protected $useTimestamps = false;
    // Especifica el campo para soft delete (debe coincidir con el nombre en la BD).
    protected $deletedField = 'deleted_at';

    // Define las reglas de validación que se aplicarán antes de guardar o actualizar.
    protected $validationRules = [
        // 'id' no es requerido para la creación.
        'id' => 'permit_empty|integer', // Cambiado a permit_empty para permitir la creación
        // 'dni' es obligatorio, debe tener exactamente 8 dígitos numéricos y ser único en la tabla.
        'dni'   => 'required|regex_match[/^[0-9]{8}$/]|is_unique[Estudiante.dni,id,{id}]',
        // 'nombre_estudiante' es obligatorio, con una longitud entre 2 y 80 caracteres.
        'nombre_estudiante'  => 'required|min_length[2]|max_length[80]',
        // 'edad' es obligatorio y debe ser un número de 1 o 2 dígitos.
        'edad'  => 'required|regex_match[/^[0-9]{1,2}$/]',
        // 'email' es obligatorio, debe ser un formato de email válido y tener un máximo de 50 caracteres.
        'email' => 'required|valid_email|max_length[50]',
    ];
    // Define mensajes de error personalizados para las reglas de validación.
    protected $validationMessages = [
        'dni' => [
            'regex_match'=>'El DNI debe tener 8 dígitos.',
            'is_unique'=>'El DNI ya existe.'
        ],
        'edad' => [
            'regex_match'=>'La edad debe ser numérica y de 1 o 2 dígitos.'
        ],
        'email' => [
            'valid_email' => 'El campo de email debe contener una dirección de correo válida.'
        ],
    ];

    // --- Métodos Personalizados ---

    /**
     * Obtiene todos los estudiantes junto con el nombre de su carrera.
     * Utiliza un 'left join' para asegurar que se muestren todos los estudiantes,
     * incluso aquellos que no están inscritos en ninguna carrera.
     * Nota: Con soft delete, solo se devuelven estudiantes no borrados (deleted_at IS NULL).
     * @return array
     */
    public function getEstudiantesConCarrera()
    {
        // Construye una consulta SELECT que une la tabla 'Estudiante' con 'Carrera'.
        return $this->select('Estudiante.*, Carrera.nombre_carrera')
            ->join('Carrera', 'Carrera.id = Estudiante.carrera_id', 'left') // 'left' join para incluir estudiantes sin carrera.
            ->findAll(); // Ejecuta la consulta y devuelve todos los resultados (excluyendo borrados lógicamente).
    }

    /**
     * Obtiene un estudiante específico por ID, junto con el nombre de su carrera.
     * Nota: Si el estudiante está borrado lógicamente, devolverá null.
     * @param int $id El ID del estudiante.
     * @return array|object|null Devuelve el estudiante o null si no se encuentra o está borrado.
     */
    public function getEstudianteConCarrera($id)
    {
        // Construye la misma consulta que el método anterior, pero para un solo registro.
        return $this->select('Estudiante.*, Carrera.nombre_carrera')
            ->join('Carrera', 'Carrera.id = Estudiante.carrera_id', 'left')
            ->find($id); // Ejecuta la consulta y devuelve solo el registro que coincide con el ID (si no está borrado).
    }

    /**
     * Obtiene todos los estudiantes de una carrera específica.
     * Nota: Solo incluye estudiantes no borrados lógicamente.
     * @param int $careerId El ID de la carrera.
     * @return array Devuelve un array de estudiantes.
     */
    public function getEstudiantesPorCarrera($careerId)
    {
        // Filtra los estudiantes por la columna 'carrera_id' y une con Carrera para obtener el nombre.
        return $this->select('Estudiante.*, Carrera.nombre_carrera')
            ->join('Carrera', 'Carrera.id = Estudiante.carrera_id', 'left')
            ->where('Estudiante.carrera_id', $careerId)
            ->findAll(); // Devuelve todos los estudiantes que coinciden con el filtro (excluyendo borrados).
    }

    /**
     * Obtiene las notas de un estudiante específico.
     * Nota: Para mantener consistencia con soft delete, considera filtrar notas no borradas si aplica.
     * @param int $id_est El ID del estudiante.
     * @return array Devuelve un array de notas con información de la materia.
     */
    public function getNotas($id_est)
    {
        // Cambié a usar el modelo de Nota para respetar soft delete si está implementado allí.
        // Si NotaModel tiene soft delete, esto excluirá notas borradas.
        $notaModel = new \App\Models\NotaModel();
        return $notaModel->select('Nota.*, Materia.nombre_materia')
            ->join('Materia', 'Materia.id = Nota.materia_id')
            ->where('Nota.estudiante_id', $id_est)
            ->findAll();
    }

    /**
     * Obtiene las materias inscritas de un estudiante específico.
     * Nota: Para mantener consistencia con soft delete, considera filtrar inscripciones no borradas si aplica.
     * @param int $id_est El ID del estudiante.
     * @return array Devuelve un array de inscripciones con información de la materia.
     */
    public function getMateriasInscritas($id_est)
    {
        // Cambié a usar el modelo de Inscripcion para respetar soft delete si está implementado allí.
        // Si InscripcionModel tiene soft delete, esto excluirá inscripciones borradas.
        $inscripcionModel = new \App\Models\InscripcionModel();
        return $inscripcionModel->select('Inscripcion.*, Materia.nombre_materia, Materia.codigo_materia, Materia.id as materia_id')
            ->join('Materia', 'Materia.id = Inscripcion.materia_id')
            ->where('Inscripcion.estudiante_id', $id_est)
            ->findAll();
    }

    /**
     * Obtiene estadísticas de un estudiante específico.
     * @param int $id_est El ID del estudiante.
     * @return array Devuelve un array con promedio general, progreso, etc.
     */
    public function getEstadisticas(array $notas, array $inscritas): array
    {
        // Promedio general
        $totalNotas = count($notas);
        $sumaNotas = 0;
        foreach ($notas as $nota) {
            $sumaNotas += $nota['calificacion'];
        }
        $promedio = $totalNotas > 0 ? round($sumaNotas / $totalNotas, 2) : 0;

        // Materias aprobadas (asumiendo >= 6)
        $aprobadas = 0;
        foreach ($notas as $nota) {
            if ($nota['calificacion'] >= 6) {
                $aprobadas++;
            }
        }

        // Total de materias inscritas
        $totalInscritas = count($inscritas);

        // Progreso (aprobadas / total inscritas * 100)
        $progreso = $totalInscritas > 0 ? round(($aprobadas / $totalInscritas) * 100, 2) : 0;

        return [
            'promedio_general' => $promedio,
            'materias_aprobadas' => $aprobadas,
            'materias_pendientes' => $totalInscritas - $aprobadas,
            'progreso' => $progreso
        ];
    }

    /**
     * Obtiene las asistencias individuales de un estudiante para una materia específica.
     * Nota: Para mantener consistencia con soft delete, considera filtrar asistencias no borradas si aplica.
     * @param int $inscripcion_id ID de la inscripción (conecta estudiante y materia).
     * @return array Lista de asistencias con fecha y estado.
     */
    public function getAsistenciasIndividuales($inscripcion_id)
    {
        // Cambié a usar el modelo de Asistencia para respetar soft delete si está implementado allí.
        // Si AsistenciaModel tiene soft delete, esto excluirá asistencias borradas.
        $asistenciaModel = new \App\Models\AsistenciaModel();
        return $asistenciaModel->where('inscripcion_id', $inscripcion_id)
            ->select('fecha, estado')
            ->orderBy('fecha', 'ASC')
            ->findAll();
    }

    /**
     * Obtiene y calcula los datos de asistencia de un estudiante para una materia específica.
     * Nota: Para mantener consistencia con soft delete, considera filtrar asistencias no borradas si aplica.
     * @param int $inscripcion_id ID de la inscripción (conecta estudiante y materia).
     * @return array|null Datos calculados de asistencia o null si no se encuentra.
     */
    public function getDatosAsistencia($inscripcion_id)
    {
        // Paso 1: Obtener la inscripción y los datos de la materia.
        // Usamos el modelo de Inscripcion para respetar soft delete.
        $inscripcionModel = new \App\Models\InscripcionModel();
        $inscripcion = $inscripcionModel->join('materia m', 'm.id = Inscripcion.materia_id')
            ->where('Inscripcion.id', $inscripcion_id)
            ->select('Inscripcion.id as inscripcion_id, m.id as materia_id, m.nombre_materia')
            ->first();

        if (!$inscripcion) {
            return null; // Si no hay inscripción o está borrada, no hay nada que calcular.
        }

        // Paso 2: Contar las asistencias del estudiante.
        // Usamos el modelo de Asistencia para respetar soft delete.
        $asistenciaModel = new \App\Models\AsistenciaModel();
        $asistencias = $asistenciaModel->where('inscripcion_id', $inscripcion_id)
            ->select("COUNT(CASE WHEN estado = 'presente' THEN 1 END) as presentes")
            ->select("COUNT(CASE WHEN estado = 'ausente' THEN 1 END) as ausentes")
            ->select("COUNT(CASE WHEN estado = 'justificado' THEN 1 END) as justificados")
            ->first();

        $total_clases_registradas = ($asistencias['presentes'] ?? 0) + ($asistencias['ausentes'] ?? 0);
        $faltas_totales = $asistencias['ausentes'] ?? 0;

        // Paso 3: Calcular porcentajes y datos para el gráfico.
        $porcentaje_asistencia = ($total_clases_registradas > 0) ? (($asistencias['presentes'] ?? 0) / $total_clases_registradas) * 100 : 100;

        // Paso 4: Definir reglas y calcular faltas restantes y estado.
        // Usamos valores fijos como me pediste, en lugar de leerlos de la BD.
        $max_faltas_promocion = 5;
        $porcentaje_minimo_regular = 80;

        // Calculamos el máximo de faltas para regularizar basado en el 80% de asistencia.
        // Si hay 10 clases, se necesita 80% de asistencia, puede faltar a 2 (20%).
        $max_faltas_regulares = floor($total_clases_registradas * (1 - ($porcentaje_minimo_regular / 100)));

        $faltas_restantes_promocion = $max_faltas_promocion - $faltas_totales;

        $estado_asistencia = 'ok';
        if ($faltas_totales > $max_faltas_promocion) {
            $estado_asistencia = 'solo_regular'; // Perdió promoción por faltas
        }
        if ($porcentaje_asistencia < $porcentaje_minimo_regular && $total_clases_registradas > 0) {
            $estado_asistencia = 'libre'; // Quedó libre por faltas
        }

        // Paso 5: Devolver todos los datos estructurados.
        return [
            'materia' => $inscripcion,
            'asistencias' => $asistencias,
            'porcentaje_asistencia' => round($porcentaje_asistencia, 2),
            'faltas_restantes_promocion' => $faltas_restantes_promocion > 0 ? $faltas_restantes_promocion : 0,
            'faltas_restantes_regular' => ($max_faltas_regulares - $faltas_totales) > 0 ? ($max_faltas_regulares - $faltas_totales) : 0,
            'estado_asistencia' => $estado_asistencia
        ];
    }
}