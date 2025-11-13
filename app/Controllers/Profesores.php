<?php

// Define el espacio de nombres del controlador.
namespace App\Controllers;
use App\Controllers\BaseController;
// Importa los modelos que este controlador necesita para funcionar.
use App\Models\ProfesorModel;
use App\Models\CarreraModel;
use App\Models\MateriaModel;

/**
 * Este es el "director de orquesta" para todo lo relacionado con los profesores.
 * Cada método público corresponde a una acción que el usuario puede realizar,
 * como ver la lista, registrar uno nuevo, editar, etc.
 */
class Profesores extends BaseController
{
    /**
     * Propósito: Muestra la página principal de gestión de profesores.
     * Tareas:
     * 1. Carga el modelo de Profesores.
     * 2. Pide al modelo la lista completa.
     * 3. Pasa todos estos datos a la vista 'profesores.php' para que los muestre.
     * @return string La vista renderizada.
     */
    public function index()
    {
        // Para evitar error de conexión a base de datos, mostramos la vista con datos vacíos si no hay conexión.
        try {
            // Instancia los modelos necesarios.
            $profesorModel = new ProfesorModel();
            $carreraModel = new CarreraModel();
            $materiaModel = new \App\Models\MateriaModel();

            // Prepara un array '$data' para pasar información a la vista.
            $data['profesores'] = $profesorModel->getProfesores();
            $data['carreras'] = $carreraModel->findAll();
            $data['materias'] = $materiaModel->findAll();
        } catch (\Exception $e) {
            // Si hay error, mostramos la vista con datos vacíos.
            $data['profesores'] = [];
            $data['carreras'] = [];
            $data['materias'] = [];
        }

        // Carga la vista 'profesores.php' y le pasa el array '$data'.
        return view('administrador/profesores', $data);
    }

    /**
     * Método: registrar()
     * Propósito: Procesa el envío del formulario para crear un nuevo profesor.
     * Tareas:
     * 1. Recoge los datos enviados por el usuario a través del método POST.
     * 2. Llama al método `save()` del modelo. Este método intenta guardar los datos,
     *    pero primero ejecuta las reglas de validación definidas en el modelo.
     * 3. Si la validación falla, `save()` devuelve `false`. El controlador redirige al usuario de vuelta
     *    al formulario (`redirect()->back()`), manteniendo los datos que ya había ingresado (`withInput()`)
     *    y enviando los errores de validación (`with('errors', ...)`).
     * 4. Si todo es correcto, redirige a la página de profesores con un mensaje de éxito.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        $profesorModel = new ProfesorModel();

        $nombreProfesor = $this->request->getPost('nombre_profesor');
        $legajo = $this->request->getPost('legajo');

        // Verificar si el profesor ya existe
        $profesorExistente = $profesorModel->where('nombre_profesor', $nombreProfesor)->first();
        if ($profesorExistente) {
            return redirect()->to('/administrador/profesores')->withInput()->with('errors', ['El profesor ya está registrado en el sistema.']);
        }

        // Recoge los datos del formulario usando el objeto 'request'.
        $data = [
            'legajo' => $legajo,
            'nombre_profesor'  => $nombreProfesor,
            'carrera_id' => $this->request->getPost('carrera_id'),
        ];

        // Intenta guardar los datos. El modelo se encarga de la validación.
        if ($profesorModel->save($data) === false) {
            // Si la validación falla, redirige hacia atrás con los errores.
            return redirect()->to('/administrador/profesores')->withInput()->with('errors', 'Error al registrar: ' . implode(', ', $profesorModel->errors()));
        }

        // Si el guardado es exitoso, redirige a la lista de profesores con un mensaje de éxito.
        return redirect()->to('/administrador/profesores')->with('success', 'Profesor registrado correctamente con legajo: ' . $legajo);
    }

    /**
     * Método: assignMateria()
     * Propósito: Asigna una materia a un profesor actualizando el campo materia_id.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function assignMateria()
    {
        $profesorId = $this->request->getPost('profesor_id');
        $materiaId = $this->request->getPost('materia_id');

        if (!$profesorId || !$materiaId) {
            return redirect()->to('/administrador/profesores')->with('error', 'Datos incompletos para asignar materia.');
        }

        $profesorModel = new ProfesorModel();
        $materiaModel = new MateriaModel();

        // Verificar que el profesor existe
        $profesor = $profesorModel->find($profesorId);
        if (!$profesor) {
            return redirect()->to('/administrador/profesores')->with('error', 'Profesor no encontrado.');
        }

        // Verificar que la materia existe
        $materia = $materiaModel->find($materiaId);
        if (!$materia) {
            return redirect()->to('/administrador/profesores')->with('error', 'Materia no encontrada.');
        }

        // Verificar que la materia no esté ya asignada a otro profesor
        $profesorExistente = $profesorModel->where('materia_id', $materiaId)->first();
        if ($profesorExistente && $profesorExistente['id'] != $profesorId) {
            return redirect()->to('/administrador/profesores')->with('error', 'Esta materia ya está asignada a otro profesor.');
        }

        // Actualizar el profesor con la nueva materia
        $data = ['materia_id' => $materiaId];
        if ($profesorModel->update($profesorId, $data)) {
            return redirect()->to('/administrador/profesores')->with('success', 'Materia asignada correctamente al profesor.');
        } else {
            return redirect()->to('/administrador/profesores')->with('error', 'Error al asignar la materia.');
        }
    }

    /**
     * Método: buscarProfesorPorNombre()
     * Propósito: Busca un profesor por nombre para mostrar su legajo existente o generar uno nuevo.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function buscarProfesorPorNombre()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $nombreProfesor = $this->request->getPost('nombre_profesor');
        $profesorModel = new ProfesorModel();

        // Buscar si el profesor ya existe
        $profesorExistente = $profesorModel->where('nombre_profesor', $nombreProfesor)->first();

        if ($profesorExistente) {
            // Si existe, devolver el legajo existente y marcar como existente
            return $this->response->setJSON([
                'existe' => true,
                'legajo' => $profesorExistente['legajo'],
                'mensaje' => 'El profesor ya está registrado en el sistema.'
            ]);
        } else {
            // Si no existe, generar un legajo nuevo
            $legajoNuevo = $profesorModel->generarLegajo();
            return $this->response->setJSON([
                'existe' => false,
                'legajo' => $legajoNuevo
            ]);
        }
    }

    /**
     * Propósito: Obtener los datos de un único profesor para poder editarlos.
     * Tareas:
     * 1. Está diseñado para responder a una petición AJAX (hecha desde el archivo app.js).
     * 2. Busca al profesor por el ID proporcionado.
     * 3. Devuelve los datos del profesor en formato JSON para que JavaScript pueda
     *    rellenar el formulario del modal de edición.
     * @param int $id El ID del profesor.
     */
    public function edit($id)
    {
        $profesorModel = new ProfesorModel();
        $profesor = $profesorModel->find($id);

        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            if ($profesor) {
                // Si se encuentra el profesor, devuelve sus datos como una respuesta JSON.
                return $this->response->setJSON($profesor);
            } else {
                // Si no se encuentra, devuelve un error 404 (No Encontrado) con un mensaje.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Profesor no encontrado']);
            }
        }
        // Si no es AJAX, podrías redirigir o mostrar una vista de error.
        // Por ahora, se asume que siempre será una llamada AJAX.
    }

    /**
     * Método: update($id)
     * Propósito: Procesa el envío del formulario de edición para actualizar un profesor.
     * Tareas:
     * 1. Recoge los datos del formulario de edición.
     * 2. Llama al método `update()` del modelo, pasándole el ID del profesor a modificar y los nuevos datos.
     *    Este método también ejecuta las validaciones.
     * 3. Redirige a la página de profesores con un mensaje de éxito o error.
     * @param int $id El ID del profesor a actualizar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $profesorModel = new ProfesorModel();
        // Recoge todos los datos del formulario de edición.
        $data = $this->request->getPost();
        // Añade el ID a los datos para que la regla de validación 'is_unique' pueda ignorar el registro actual.
        $data['id'] = $id;

        // Intenta actualizar los datos. El modelo se encarga de la validación.
        if ($profesorModel->update($id, $data) === false) {
            return redirect()->to('/administrador/profesores')->withInput()->with('errors', 'Error al actualizar: ' . implode(', ', $profesorModel->errors()));
        }

        // Si la actualización es exitosa, redirige con un mensaje de éxito.
        return redirect()->to('/administrador/profesores')->with('success', 'Profesor actualizado correctamente.');
    }

    /**
     * Método: delete($id)
     * Propósito: Elimina un profesor de la base de datos.
     * Tareas:
     * 1. Llama al método `delete()` del modelo, pasándole el ID del profesor a eliminar.
     * 2. Redirige a la página de profesores con un mensaje de confirmación.
     * @param int $id El ID del profesor a eliminar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $profesorModel = new ProfesorModel();
        // Intenta eliminar el registro.
        if ($profesorModel->delete($id)) {
            return redirect()->to('/profesores')->with('success', 'Profesor eliminado correctamente.');
        } else {
            // Si por alguna razón falla (ej: un callback del modelo lo impide), redirige con un error.
            return redirect()->to('/profesores')->with('error', 'No se pudo eliminar al profesor.');
        }
    }

    /**
     * Método: search($id)
     * Propósito: Busca un profesor por su ID para mostrar sus detalles.
     * Tareas:
     * 1. Responde a una petición AJAX desde el formulario "Buscar por ID".
     * 2. Usa un método personalizado del modelo para obtener el profesor.
     * 3. Devuelve los datos en formato JSON.
     * @param int $id El ID del profesor a buscar.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function search($id)
    {
        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            $profesorModel = new ProfesorModel();
            // Llama al método personalizado que obtiene el profesor.
            $profesor = $profesorModel->getProfesor($id);

            if ($profesor) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($profesor);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Profesor no encontrado con ese ID.']);
            }
        }
    }

    /**
     * Método: searchByLegajo($legajo)
     * Propósito: Busca un profesor por su legajo para mostrar sus detalles.
     * Tareas:
     * 1. Responde a una petición AJAX desde el formulario "Buscar por Legajo".
     * 2. Usa un método personalizado del modelo para obtener el profesor.
     * 3. Devuelve los datos en formato JSON.
     * @param int $legajo El legajo del profesor a buscar.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function searchByLegajo($legajo)
    {
        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            $profesorModel = new ProfesorModel();
            // Llama al método personalizado que obtiene el profesor por legajo.
            $profesor = $profesorModel->getProfesorByLegajo($legajo);

            if ($profesor) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($profesor);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Profesor no encontrado con ese legajo.']);
            }
        }
    }

    /**
     * Método: dashboard()
     * Propósito: Muestra el dashboard del profesor con datos de la base de datos.
     * @return string La vista renderizada.
     */
    public function dashboard()
    {
        $session = session();
        $id_prof = $session->get('id_usuario');
        $rol_id = $session->get('rol_id');

        // 1. Verificar si hay sesión activa
        if (!$id_prof) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión para ver su dashboard.');
        }

        // 2. Verificar si el rol es correcto (debe ser profesor)
        if ($rol_id != 2) { // El rol de profesor es 2
            // Si es un estudiante, lo redirigimos a su propio dashboard.
            if ($rol_id == 3) { // El rol de estudiante es 3
                return redirect()->to('estudiantes/dashboard');
            }
            // Para cualquier otro rol, lo enviamos al inicio.
            return redirect()->to('/')->with('error', 'Acceso no autorizado.');
        }

        // Instanciamos los modelos necesarios
        $profesorModel = new ProfesorModel();
        $notaModel = new \App\Models\NotaModel();

        // Obtenemos los datos del profesor
        $data['profesor'] = $profesorModel->getProfesorConCarrera($id_prof);

        // Obtenemos todas las materias que dicta el profesor
        $todas_las_materias = $profesorModel->getMateriasDictadas($id_prof);

        // Agrupamos las materias por carrera
        $materias_por_carrera = [];
        foreach ($todas_las_materias as $materia) {
            $carrera_id = $materia['carrera_id'];
            if (!isset($materias_por_carrera[$carrera_id])) {
                $materias_por_carrera[$carrera_id] = [
                    'nombre_carrera' => $materia['nombre_carrera'],
                    'materias' => []
                ];
            }
            $materias_por_carrera[$carrera_id]['materias'][] = $materia;
        }

        // Preparamos los arrays para organizar los datos por materia
        $estudiantes_por_materia = [];
        $notas_por_materia = [];
        $asistencias_por_materia = [];

        // Iteramos sobre cada materia para obtener sus datos asociados
        foreach ($todas_las_materias as $materia) {
            $materia_id = $materia['id'];
            $estudiantes_por_materia[$materia_id] = $profesorModel->getEstudiantesPorMateriaEspecifica($materia_id);
            $notas_por_materia[$materia_id] = $notaModel->getNotasPorMateria($materia_id);
            $asistencias_por_materia[$materia_id] = $profesorModel->getDetalleAsistenciaPorMateria($materia_id);
        }

        // Pasamos todos los datos organizados a la vista
        $data['materias'] = $todas_las_materias;
        $data['estudiantes_por_materia'] = $estudiantes_por_materia;
        $data['notas_por_materia'] = $notas_por_materia;
        $data['asistencias_por_materia'] = $asistencias_por_materia;

        return view('Dashboard_Profesores/dashboard_profesor', $data);
    }

    /**
     * Método: carreras()
     * Propósito: Muestra la lista de carreras asignadas al profesor.
     * @return string La vista renderizada.
     */
    public function carreras()
    {
        // Verificamos si el usuario ha iniciado sesión.
        // La sesión 'id_usuario' corresponde al ID de la tabla 'usuarios'.
        // Asumimos que para profesores, el id_usuario es el mismo que el id_prof.
        // Si tu lógica es diferente, ajústalo aquí.
        if (! $id_prof = session()->get('id_usuario')) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión para ver su dashboard.');
        }

        // Instanciamos los modelos necesarios
        $profesorModel = new ProfesorModel();
        $notaModel = new \App\Models\NotaModel();

        // Obtenemos los datos del profesor
        $data['profesor'] = $profesorModel->getProfesorConCarrera($id_prof);

        // Obtenemos todas las materias que dicta el profesor
        $todas_las_materias = $profesorModel->getMateriasDictadas($id_prof);
        
        // Preparamos los arrays para organizar los datos por materia
        $estudiantes_por_materia = [];
        $notas_por_materia = [];
        $asistencias_por_materia = [];

        // Iteramos sobre cada materia para obtener sus datos asociados
        foreach ($todas_las_materias as $materia) {
            $materia_id = $materia['id'];
            $estudiantes_por_materia[$materia_id] = $profesorModel->getEstudiantesPorMateriaEspecifica($materia_id);
            $notas_por_materia[$materia_id] = $notaModel->getNotasPorMateria($materia_id);
            $asistencias_por_materia[$materia_id] = $profesorModel->getDetalleAsistenciaPorMateria($materia_id);
        }

        // Pasamos todos los datos organizados a la vista
        $data['materias'] = $todas_las_materias;
        $data['estudiantes_por_materia'] = $estudiantes_por_materia;
        $data['notas_por_materia'] = $notas_por_materia;
        $data['asistencias_por_materia'] = $asistencias_por_materia;

        return view('Dashboard_Profesores/carreras', $data);
    }

    /**
     * Método: carrera($carrera_id)
     * Propósito: Muestra los detalles de una carrera específica, incluyendo estudiantes inscritos.
     * @param int $carrera_id El ID de la carrera.
     * @return string La vista renderizada.
     */
    public function carrera($carrera_id)
    {
        // Por ahora, usar profesor con ID 1. En el futuro, usar sesión.
        $id_prof = 1;

        $profesorModel = new ProfesorModel();
        $carreraModel = new CarreraModel();

        $data['profesor'] = $profesorModel->getProfesorConCarrera($id_prof);
        $data['carrera'] = $carreraModel->find($carrera_id);
        $data['estudiantes'] = $profesorModel->getEstudiantesPorCarrera($carrera_id, $id_prof);

        return view('Dashboard_Profesores/carrera_detalle', $data);
    }

    /**
     * Método: guardarNotas()
     * Propósito: Guarda las notas de los estudiantes para una materia específica.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function guardarNotas()
    {
        $materiaId = $this->request->getPost('materia_id');
        $notas = $this->request->getPost('nota');
        $fechas = $this->request->getPost('fecha_evaluacion');
        $observaciones = $this->request->getPost('observaciones');

        if (!$materiaId || !$notas) {
            return redirect()->back()->with('error', 'Datos incompletos para guardar notas.');
        }

        $notaModel = new \App\Models\NotaModel();

        foreach ($notas as $estudianteId => $calificacion) {
            if (!empty($calificacion)) {
                $data = [
                    'estudiante_id' => $estudianteId,
                    'materia_id' => $materiaId,
                    'calificacion' => $calificacion,
                    'fecha_evaluacion' => $fechas[$estudianteId] ?? date('Y-m-d'),
                    'observaciones' => $observaciones[$estudianteId] ?? ''
                ];

                // Buscar si ya existe una nota para este estudiante y materia
                $existing = $notaModel->where('estudiante_id', $estudianteId)
                                     ->where('materia_id', $materiaId)
                                     ->first();

                if ($existing) {
                    $notaModel->update($existing['id'], $data);
                } else {
                    $notaModel->insert($data);
                }
            }
        }

        return redirect()->back()->with('success', 'Notas guardadas correctamente.');
    }

    /**
     * Método: getEstudiantesMateria($materia_id)
     * Propósito: Devuelve los estudiantes de una materia en formato JSON para AJAX.
     * @param int $materia_id El ID de la materia.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getEstudiantesMateria($materia_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $profesorModel = new ProfesorModel();
        $estudiantes = $profesorModel->getEstudiantesPorMateriaEspecifica($materia_id);

        return $this->response->setJSON($estudiantes);
    }

    /**
     * Método: guardarAsistencia()
     * Propósito: Guarda la asistencia de los estudiantes para una fecha específica.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function guardarAsistencia()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $data = $this->request->getJSON(true);
        $materiaId = $data['materia_id'];
        $fecha = $data['fecha'];
        $asistencias = $data['asistencias'];

        if (!$materiaId || !$fecha || !$asistencias) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();

        foreach ($asistencias as $asistencia) {
            $dataAsistencia = [
                'estudiante_id' => $asistencia['estudiante_id'],
                'materia_id' => $materiaId,
                'fecha' => $fecha,
                'estado' => $asistencia['estado']
            ];

            // Buscar si ya existe asistencia para este estudiante, materia y fecha
            $existing = $asistenciaModel->where('estudiante_id', $asistencia['estudiante_id'])
                                       ->where('materia_id', $materiaId)
                                       ->where('fecha', $fecha)
                                       ->first();

            if ($existing) {
                $asistenciaModel->update($existing['id'], $dataAsistencia);
            } else {
                $asistenciaModel->insert($dataAsistencia);
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Asistencia guardada correctamente']);
    }

    /**
     * Método: getAsistenciaMateria($materia_id)
     * Propósito: Devuelve la asistencia de una materia en un rango de fechas para el calendario.
     * @param int $materia_id El ID de la materia.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getAsistenciaMateria($materia_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $asistencias = $asistenciaModel->where('materia_id', $materia_id)
                                      ->where('fecha >=', $start)
                                      ->where('fecha <=', $end)
                                      ->findAll();

        return $this->response->setJSON($asistencias);
    }

    /**
     * Método: controlAsistencias($materia_id)
     * Propósito: Muestra el control de asistencias mensual para una materia específica.
     * @param int $materia_id El ID de la materia.
     * @return string La vista renderizada.
     */
    public function controlAsistencias($materia_id)
    {
        // Por ahora, usar profesor con ID 1. En el futuro, usar sesión.
        $id_prof = 1;

        $profesorModel = new ProfesorModel();
        $materiaModel = new \App\Models\MateriaModel();

        $data['profesor'] = $profesorModel->getProfesorConCarrera($id_prof);
        $data['materia'] = $materiaModel->find($materia_id);

        return view('Dashboard_Profesores/control_asistencias', $data);
    }

    /**
     * Método: asistenciaMensual($materia_id)
     * Propósito: Redirige a la nueva vista de control de asistencias.
     * @param int $materia_id El ID de la materia.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function asistenciaMensual($materia_id)
    {
        return redirect()->to(base_url('profesores/control-asistencias/' . $materia_id));
    }

    /**
     * Método: getEventosAsistencia($materia_id)
     * Propósito: Devuelve los eventos de asistencia para el calendario FullCalendar.
     * @param int $materia_id El ID de la materia.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getEventosAsistencia($materia_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $asistencias = $asistenciaModel->getAsistenciaPorFecha($materia_id, $start, $end);

        $eventos = [];
        foreach ($asistencias as $asistencia) {
            $eventos[] = [
                'title' => $asistencia['presentes'] . 'P / ' . $asistencia['ausentes'] . 'A',
                'start' => $asistencia['fecha'],
                'backgroundColor' => $asistencia['ausentes'] > 0 ? '#dc3545' : '#28a745',
                'borderColor' => $asistencia['ausentes'] > 0 ? '#dc3545' : '#28a745',
                'extendedProps' => [
                    'presentes' => $asistencia['presentes'],
                    'ausentes' => $asistencia['ausentes'],
                    'justificados' => $asistencia['justificados']
                ]
            ];
        }

        return $this->response->setJSON($eventos);
    }

    /**
     * Método: getEstadisticasMes($materia_id)
     * Propósito: Devuelve las estadísticas del mes actual para una materia.
     * @param int $materia_id El ID de la materia.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getEstadisticasMes($materia_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $estadisticas = $asistenciaModel->getEstadisticasMes($materia_id);

        return $this->response->setJSON($estadisticas);
    }

    /**
     * Método: getResumenEstudiantes($materia_id)
     * Propósito: Devuelve el resumen de asistencia por estudiante para una materia.
     * @param int $materia_id El ID de la materia.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getResumenEstudiantes($materia_id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $resumen = $asistenciaModel->getResumenPorEstudiante($materia_id);

        return $this->response->setJSON($resumen);
    }

    /**
     * Método: getAsistenciasMensuales($materia_id, $mes, $anio)
     * Propósito: Devuelve las asistencias mensuales para una materia específica.
     * @param int $materia_id El ID de la materia.
     * @param int $mes El mes (0-11).
     * @param int $anio El año.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getAsistenciasMensuales($materia_id, $mes, $anio)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();

        // Calcular fechas del mes
        $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $mes + 1, 1, $anio));
        $fechaFin = date('Y-m-t', mktime(0, 0, 0, $mes + 1, 1, $anio));

        $asistencias = $asistenciaModel->where('materia_id', $materia_id)
                                      ->where('fecha >=', $fechaInicio)
                                      ->where('fecha <=', $fechaFin)
                                      ->findAll();

        return $this->response->setJSON($asistencias);
    }

    /**
     * Método: guardarAsistenciasMensuales()
     * Propósito: Guarda las asistencias mensuales para una materia específica.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function guardarAsistenciasMensuales()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $data = $this->request->getJSON(true);
        $materiaId = $data['materia_id'];
        $asistencias = $data['asistencias'];

        if (!$materiaId || !$asistencias) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();

        $resultado = $asistenciaModel->guardarAsistenciasMensuales($materiaId, $asistencias);

        if ($resultado) {
            return $this->response->setJSON(['success' => true, 'message' => 'Asistencias guardadas correctamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar las asistencias']);
        }
    }

    /**
     * Método: getDatosAsistenciaMensual($materia_id, $mes, $anio)
     * Propósito: Devuelve los datos necesarios para mostrar la tabla de asistencia mensual.
     * @param int $materia_id El ID de la materia.
     * @param int $mes El mes (1-12).
     * @param int $anio El año.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getDatosAsistenciaMensual($materia_id, $mes, $anio)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();

        $estudiantes = $asistenciaModel->getEstudiantesConAsistencia($materia_id, $mes, $anio);
        $estadisticas = $asistenciaModel->getEstadisticasMensuales($materia_id, $mes, $anio);

        return $this->response->setJSON([
            'estudiantes' => $estudiantes,
            'estadisticas' => $estadisticas,
            'dias_en_mes' => cal_days_in_month(CAL_GREGORIAN, $mes, $anio)
        ]);
    }

    /**
     * Método: marcarTodosPresentes($materia_id, $mes, $anio)
     * Propósito: Marca a todos los estudiantes como presentes en todas las fechas del mes.
     * @param int $materia_id El ID de la materia.
     * @param int $mes El mes (1-12).
     * @param int $anio El año.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function marcarTodosPresentes($materia_id, $mes, $anio)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $estudiantes = $asistenciaModel->getEstudiantesConAsistencia($materia_id, $mes, $anio);

        $asistencias = [];
        $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        foreach ($estudiantes as $estudiante) {
            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
                $asistencias[] = [
                    'estudiante_id' => $estudiante['id'],
                    'fecha' => $fecha,
                    'estado' => 'Presente'
                ];
            }
        }

        $resultado = $asistenciaModel->guardarAsistenciasMensuales($materia_id, $asistencias);

        if ($resultado) {
            return $this->response->setJSON(['success' => true, 'message' => 'Todos marcados como presentes']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al marcar todos como presentes']);
        }
    }

    /**
     * Método: marcarTodosAusentes($materia_id, $mes, $anio)
     * Propósito: Marca a todos los estudiantes como ausentes en todas las fechas del mes.
     * @param int $materia_id El ID de la materia.
     * @param int $mes El mes (1-12).
     * @param int $anio El año.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function marcarTodosAusentes($materia_id, $mes, $anio)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $estudiantes = $asistenciaModel->getEstudiantesConAsistencia($materia_id, $mes, $anio);

        $asistencias = [];
        $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        foreach ($estudiantes as $estudiante) {
            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
                $asistencias[] = [
                    'estudiante_id' => $estudiante['id'],
                    'fecha' => $fecha,
                    'estado' => 'Ausente'
                ];
            }
        }

        $resultado = $asistenciaModel->guardarAsistenciasMensuales($materia_id, $asistencias);

        if ($resultado) {
            return $this->response->setJSON(['success' => true, 'message' => 'Todos marcados como ausentes']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al marcar todos como ausentes']);
        }
    }

    /**
     * Método: resetearAsistencias($materia_id, $mes, $anio)
     * Propósito: Elimina todas las asistencias del mes para una materia.
     * @param int $materia_id El ID de la materia.
     * @param int $mes El mes (1-12).
     * @param int $anio El año.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function resetearAsistencias($materia_id, $mes, $anio)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        $asistenciaModel = new \App\Models\AsistenciaModel();
        $fechaInicio = sprintf('%04d-%02d-01', $anio, $mes);
        $fechaFin = date('Y-m-t', strtotime($fechaInicio));

        $resultado = $asistenciaModel->where('materia_id', $materia_id)
                                   ->where('fecha >=', $fechaInicio)
                                   ->where('fecha <=', $fechaFin)
                                   ->delete();

        if ($resultado) {
            return $this->response->setJSON(['success' => true, 'message' => 'Asistencias reseteadas correctamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al resetear asistencias']);
        }
    }

    /**
     * Método: getTablaAsistenciaMensual($materia_id, $mes, $anio)
     * Propósito: Devuelve la tabla HTML de asistencia mensual renderizada.
     * @param int $materia_id El ID de la materia.
     * @param int $mes El mes (1-12).
     * @param int $anio El año.
     * @return string HTML de la tabla.
     */
    public function getTablaAsistenciaMensual($materia_id, $mes = null, $anio = null)
    {
        // Usar mes y año actuales si no se especifican
        if ($mes === null) $mes = date('n');
        if ($anio === null) $anio = date('Y');

        $asistenciaModel = new \App\Models\AsistenciaModel();

        $estudiantes = $asistenciaModel->getEstudiantesConAsistencia($materia_id, $mes, $anio);
        $estadisticas = $asistenciaModel->getEstadisticasMensuales($materia_id, $mes, $anio);
        $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        // Nombres de los días de la semana
        $diasSemana = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

        // Generar HTML de la tabla
        $html = '<table class="table table-striped table-hover attendance-table" id="attendance-table-' . $materia_id . '" data-materia-id="' . $materia_id . '">';
        $html .= '<thead class="table-dark"><tr>';
        $html .= '<th class="text-center">Estudiante</th>';

        // Encabezados de días
        for ($dia = 1; $dia <= $diasEnMes; $dia++) {
            $fecha = mktime(0, 0, 0, $mes, $dia, $anio);
            $diaSemana = $diasSemana[date('w', $fecha)];
            $esFinDeSemana = (date('w', $fecha) == 0 || date('w', $fecha) == 6);
            $claseFinSemana = $esFinDeSemana ? 'table-secondary' : '';
            $html .= '<th class="text-center ' . $claseFinSemana . '" title="' . $diaSemana . '">' . $dia . '<br><small>' . $diaSemana . '</small></th>';
        }

        $html .= '<th class="text-center">%</th></tr></thead><tbody>';

        // Filas de estudiantes
        foreach ($estudiantes as $estudiante) {
            $html .= '<tr>';
            $html .= '<td class="fw-bold">' . esc($estudiante['nombre_estudiante'] . ' ' . $estudiante['apellido_estudiante']) . '</td>';

            // Celdas de asistencia por día
            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
                $estado = isset($estudiante['asistencia'][$dia]) ? $estudiante['asistencia'][$dia] : '';
                $checked = ($estado === 'Presente') ? 'checked' : '';
                $fechaObj = mktime(0, 0, 0, $mes, $dia, $anio);
                $esFinDeSemana = (date('w', $fechaObj) == 0 || date('w', $fechaObj) == 6);
                $claseFinSemana = $esFinDeSemana ? 'table-light' : '';

                $html .= '<td class="text-center ' . $claseFinSemana . '">';
                $html .= '<div class="form-check form-check-inline">';
                $html .= '<input class="form-check-input attendance-checkbox" type="checkbox" ';
                $html .= 'data-materia-id="' . $materia_id . '" ';
                $html .= 'data-estudiante="' . $estudiante['id'] . '" ';
                $html .= 'data-fecha="' . $fecha . '" ' . $checked . '>';
                $html .= '</div></td>';
            }

            // Calcular porcentaje
            $diasPresentes = 0;
            for ($dia = 1; $dia <= $diasEnMes; $dia++) {
                if (isset($estudiante['asistencia'][$dia]) && $estudiante['asistencia'][$dia] === 'Presente') {
                    $diasPresentes++;
                }
            }
            $porcentaje = $diasEnMes > 0 ? round(($diasPresentes / $diasEnMes) * 100, 1) : 0;
            $colorClass = $porcentaje >= 90 ? 'text-success' : ($porcentaje >= 70 ? 'text-warning' : 'text-danger');

            $html .= '<td class="text-center fw-bold ' . $colorClass . '">' . $porcentaje . '%</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        return $html;
    }
}
