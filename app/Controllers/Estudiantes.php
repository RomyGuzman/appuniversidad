<?php

// Define el espacio de nombres del controlador.
namespace App\Controllers;
use App\Controllers\BaseController;
// Importa los modelos que este controlador necesita para funcionar.
use App\Models\EstudianteModel;
use App\Models\CarreraModel;
use App\Models\MateriaModel;
use App\Models\InscripcionModel;
use App\Models\UsuarioModel;
// Importa la excepción de base de datos para manejar errores de duplicados.
use \CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * Este es el "director de orquesta" para todo lo relacionado con los estudiantes.
 * Cada método público corresponde a una acción que el usuario puede realizar,
 * como ver la lista, registrar uno nuevo, editar, etc.
 * Ahora incluye soporte para soft delete (borrado lógico) en el modelo EstudianteModel.
 */
class Estudiantes extends BaseController
{
    /**
     * Propósito: Muestra la página principal de gestión de estudiantes.
     * Tareas:
     * 1. Carga el modelo de Estudiantes y el de Carreras.
     * 2. Pide al modelo de Estudiantes la lista completa (con el nombre de la carrera).
     * 3. Pide al modelo de Carreras todas las carreras disponibles (para los menús desplegables).
     * 4. Pasa todos estos datos a la vista 'estudiantes.php' para que los muestre.
     * Nota: Con soft delete, solo se muestran estudiantes no borrados (deleted_at IS NULL).
     * @return string La vista renderizada.
     */
    public function index()
    {
        // Para evitar error de conexión a base de datos, mostramos la vista con datos vacíos si no hay conexión.
        try {
            // Instancia los modelos necesarios.
            $estudianteModel = new EstudianteModel();
            $carreraModel = new CarreraModel();

            // Prepara un array '$data' para pasar información a la vista.
            $data['estudiantes'] = $estudianteModel->getEstudiantesConCarrera();
            $data['carreras'] = $carreraModel->findAll();
        } catch (\Exception $e) {
            // Si hay error, mostramos la vista con datos vacíos.
            $data['estudiantes'] = [];
            $data['carreras'] = [];
        }

        // Carga la vista 'estudiantes.php' y le pasa el array '$data'.
        return view('administrador/estudiantes', $data);
    }

    /**
     * Método: registrar()
     * Propósito: Procesa el envío del formulario para crear un nuevo estudiante.
     * Tareas:
     * 1. Recoge los datos enviados por el usuario a través del método POST.
     * 2. Llama al método `save()` del modelo. Este método intenta guardar los datos,
     *    pero primero ejecuta las reglas de validación definidas en el modelo.
     * 3. Si la validación falla, `save()` devuelve `false`. El controlador redirige al usuario de vuelta
     *    al formulario (`redirect()->back()`), manteniendo los datos que ya había ingresado (`withInput()`)
     *    y enviando los errores de validación (`with('errors', ...)`).
     * 4. Si todo es correcto, redirige a la página de estudiantes con un mensaje de éxito.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function registrar()
    {
        $estudianteModel = new EstudianteModel();

        // Recoge los datos del formulario usando el objeto 'request'.
        $data = [
            'dni'               => $this->request->getPost('dni'),
            'nombre_estudiante' => $this->request->getPost('nombre_estudiante'),
            'edad'              => $this->request->getPost('edad'),
            'email'             => $this->request->getPost('email'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nac') ?: null,
            'carrera_id'        => $this->request->getPost('carrera_id') ?: null,
        ];

        // Verificar si el DNI ya existe
        $existingStudent = $estudianteModel->where('dni', $data['dni'])->first();
        if ($existingStudent) {
            return redirect()->to('/estudiantes')->withInput()->with('errors', ['El DNI ya existe en el sistema. No se puede registrar dos veces el mismo estudiante.']);
        }

        // Intenta guardar los datos. El modelo se encarga de la validación.
        try {
            if ($estudianteModel->save($data) === false) {
                // Si la validación falla, redirige hacia atrás con los errores.
                return redirect()->to('/estudiantes')->withInput()->with('errors', $estudianteModel->errors());
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // Si hay un error de duplicado en la base de datos, manejar específicamente
            if (strpos($e->getMessage(), 'Duplicate entry') !== false && strpos($e->getMessage(), 'dni') !== false) {
                return redirect()->to('/estudiantes')->withInput()->with('errors', ['El DNI ya existe en el sistema. No se puede registrar dos veces el mismo estudiante.']);
            }
            // Para otros errores de base de datos, relanzar la excepción
            throw $e;
        } catch (\Exception $e) {
            // Capturar cualquier otra excepción y mostrar mensaje genérico
            return redirect()->to('/estudiantes')->withInput()->with('errors', ['Error al registrar el estudiante. Por favor, inténtelo de nuevo.']);
        }

        // Si el guardado es exitoso, redirige a la lista de estudiantes con un mensaje de éxito.
        return redirect()->to('/estudiantes')->with('success', 'Estudiante registrado correctamente.');
    }

    /**
     * Propósito: Obtener los datos de un único estudiante para poder editarlos.
     * Tareas:
     * 1. Está diseñado para responder a una petición AJAX (hecha desde el archivo app.js).
     * 2. Busca al estudiante por el ID proporcionado.
     * 3. Devuelve los datos del estudiante en formato JSON para que JavaScript pueda
     *    rellenar el formulario del modal de edición.
     * Nota: Con soft delete, si el estudiante está borrado, devolverá null y error 404.
     * @param int $id El ID del estudiante.
     */
    public function edit($id)
    {
        $estudianteModel = new EstudianteModel();
        $estudiante = $estudianteModel->find($id);

        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            if ($estudiante) {
                // Si se encuentra el estudiante, devuelve sus datos como una respuesta JSON.
                return $this->response->setJSON($estudiante);
            } else {
                // Si no se encuentra (o está borrado lógicamente), devuelve un error 404 (No Encontrado) con un mensaje.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Estudiante no encontrado']);
            }
        }
        // Si no es AJAX, podrías redirigir o mostrar una vista de error.
        // Por ahora, se asume que siempre será una llamada AJAX.
    }

    /**
     * Método: update($id)
     * Propósito: Procesa el envío del formulario de edición para actualizar un estudiante.
     * Tareas:
     * 1. Recoge los datos del formulario de edición.
     * 2. Llama al método `update()` del modelo, pasándole el ID del estudiante a modificar y los nuevos datos.
     *    Este método también ejecuta las validaciones.
     * 3. Redirige a la página de estudiantes con un mensaje de éxito o error.
     * Nota: Si el estudiante está borrado lógicamente, update() no lo encontrará y fallará.
     * @param int $id El ID del estudiante a actualizar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $estudianteModel = new EstudianteModel();
        // Recoge todos los datos del formulario de edición.
        // Se usa getPost() sin parámetros para obtener todos los datos.
        $data = [
            'id'                => $id, // Para la regla de validación 'is_unique'
            'dni'               => $this->request->getPost('dni'),
            'nombre_estudiante' => $this->request->getPost('nest'), // CORRECCIÓN: Mapeo del campo 'nest' del formulario
            'email'             => $this->request->getPost('email'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nac') ?: null, // CORRECCIÓN: Mapeo del campo 'fecha_nac'
            'carrera_id'        => $this->request->getPost('id_car') ?: null, // CORRECCIÓN: Mapeo del campo 'id_car'
        ];

        // Verificar si el DNI ya existe en otro estudiante (excluyendo el actual)
        // Usar consulta directa para mayor robustez
        $db = \Config\Database::connect();
        $existingStudent = $db->table('estudiante')
            ->where('dni', $data['dni'])
            ->where('id !=', $id)
            ->get()
            ->getRow();

        if ($existingStudent) {
            return redirect()->to('/estudiantes')->withInput()->with('errors', ['El DNI ya existe en otro estudiante. No se puede actualizar con un DNI duplicado.']);
        }

        // Intenta actualizar los datos. El modelo se encarga de la validación.
        try {
            if ($estudianteModel->update($id, $data) === false) {
                return redirect()->to('/estudiantes')->withInput()->with('errors', $estudianteModel->errors());
            }
        } catch (\Throwable $e) {
            // Capturar cualquier excepción, incluyendo DatabaseException y otras
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'Duplicate entry') !== false && strpos($errorMessage, 'dni') !== false) {
                return redirect()->to('/estudiantes')->withInput()->with('errors', ['El DNI ya existe en el sistema. No se puede actualizar con un DNI duplicado.']);
            }
            // Para cualquier otro error, mostrar mensaje genérico sin romper la aplicación
            log_message('error', 'Error al actualizar estudiante: ' . $errorMessage);
            return redirect()->to('/estudiantes')->withInput()->with('errors', ['Error al actualizar el estudiante. Por favor, inténtelo de nuevo.']);
        }

        // Si la actualización es exitosa, redirige con un mensaje de éxito.
        return redirect()->to('/estudiantes')->with('success', 'Estudiante actualizado correctamente.');
    }

    /**
     * Método: delete($id)
     * Propósito: Marca un estudiante como borrado lógicamente (soft delete).
     * Tareas:
     * 1. Llama al método `delete()` del modelo, pasándole el ID del estudiante a "eliminar".
     *    Con soft delete, esto establece deleted_at en lugar de eliminar físicamente.
     * 2. Redirige a la página de estudiantes con un mensaje de confirmación.
     * Nota: Solo usuarios autorizados (e.g., admin) deberían poder acceder a esto.
     * @param int $id El ID del estudiante a marcar como borrado.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $estudianteModel = new EstudianteModel();
        // Intenta marcar como borrado lógicamente.
        if ($estudianteModel->delete($id)) {
            return redirect()->to('/estudiantes')->with('success', 'Estudiante marcado como borrado correctamente.');
        } else {
            // Si por alguna razón falla (ej: un callback del modelo lo impide), redirige con un error.
            return redirect()->to('/estudiantes')->with('error', 'No se pudo marcar al estudiante como borrado.');
        }
    }

    /**
     * Método: restore($id)
     * Propósito: Restaura un estudiante marcado como borrado lógicamente.
     * Tareas:
     * 1. Llama al método `restore()` del modelo para quitar el flag deleted_at.
     * 2. Redirige con mensaje de éxito o error.
     * Nota: Solo para usuarios autorizados (e.g., superadmin).
     * @param int $id El ID del estudiante a restaurar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function restore($id)
    {
        $estudianteModel = new EstudianteModel();
        // Intenta restaurar el estudiante.
        if ($estudianteModel->restore($id)) {
            return redirect()->to('/estudiantes')->with('success', 'Estudiante restaurado correctamente.');
        } else {
            return redirect()->to('/estudiantes')->with('error', 'No se pudo restaurar al estudiante.');
        }
    }

    /**
     * Método: search($id)
     * Propósito: Busca un estudiante por su ID para mostrar sus detalles.
     * Tareas:
     * 1. Responde a una petición AJAX desde el formulario "Buscar por ID".
     * 2. Usa un método personalizado del modelo para obtener el estudiante y el nombre de su carrera.
     * 3. Devuelve los datos en formato JSON.
     * Nota: Con soft delete, si está borrado, devolverá null y error 404.
     * @param int $id El ID del estudiante a buscar.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function search($id = null)
    {
        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            $estudianteModel = new EstudianteModel();
            // Llama al método personalizado que une las tablas Estudiante y Carrera.
            $estudiante = $estudianteModel->getEstudianteConCarrera($id);

            if ($estudiante) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($estudiante);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Estudiante no encontrado con ese ID.']);
            }
        }
    }

    /**
     * Método: searchByCareer($careerId)
     * Propósito: Busca todos los estudiantes que pertenecen a una carrera específica.
     * Tareas:
     * 1. Responde a una petición AJAX desde el formulario "Buscar por Carrera".
     * 2. Usa un método personalizado del modelo para obtener la lista de estudiantes.
     * 3. Devuelve la lista en formato JSON para que JavaScript la muestre.
     * Nota: Solo incluye estudiantes no borrados.
     * @param int $careerId El ID de la carrera.
     * @return \CodeIgniter\HTTP\ResponseInterface|\CodeIgniter\HTTP\RedirectResponse
     */
    public function searchByCareer($careerId)
    {
        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            $estudianteModel = new EstudianteModel();
            // Llama al método del modelo que filtra estudiantes por el ID de la carrera.
            $estudiantes = $estudianteModel->getEstudiantesPorCarrera($careerId);

            // Devuelve la lista de estudiantes (incluso si está vacía) en formato JSON.
            return $this->response->setJSON($estudiantes);
        }
        // Si alguien intenta acceder a esta URL directamente desde el navegador, lo redirige.
        return redirect()->to('/estudiantes');
    }

    /**
     * Método: inscribir()
     * Propósito: Procesa la inscripción a una materia vía AJAX desde el dashboard.
     * Tareas:
     * 1. Verifica que sea una petición AJAX.
     * 2. Obtiene el estudiante logueado.
     * 3. Crea una nueva inscripción con estado 'Pendiente'.
     * 4. Devuelve respuesta JSON con éxito o error.
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function inscribir()
    {
        log_message('info', '=== INICIO MÉTODO INSCRIBIR ===');

        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no autorizado']);
        }

        // Verificar sesión
        $email_usuario = session()->get('usuario');
        if (!$email_usuario) {
            return $this->response->setJSON(['error' => 'Sesión expirada']);
        }

        try {
            $estudianteModel = new EstudianteModel();
            $inscripcionModel = new InscripcionModel();

            // Obtener estudiante
            $estudiante = $estudianteModel->where('email', $email_usuario)->first();
            if (!$estudiante) {
                return $this->response->setJSON(['error' => 'Estudiante no encontrado']);
            }

            $estudianteId = $estudiante['id'];
            $materiaId = $this->request->getPost('materia_id');

            if (!$materiaId || !is_numeric($materiaId)) {
                return $this->response->setJSON(['error' => 'Materia no especificada']);
            }

            // Verificar si ya existe una inscripción activa
            $inscripcionExistente = $inscripcionModel->where('estudiante_id', $estudianteId)
                                                    ->where('materia_id', $materiaId)
                                                    ->whereIn('estado_inscripcion', ['Pendiente', 'Confirmada', 'Aprobada'])
                                                    ->first();

            if ($inscripcionExistente) {
                return $this->response->setJSON(['error' => 'Ya estás inscrito en esta materia']);
            }

            // Crear nueva inscripción
            $dataInscripcion = [
                'estudiante_id' => $estudianteId,
                'materia_id' => $materiaId,
                'fecha_inscripcion' => date('Y-m-d'),
                'estado_inscripcion' => 'Pendiente',
                'observaciones_inscripcion' => 'Inscripción desde dashboard'
            ];

            $result = $inscripcionModel->insert($dataInscripcion);

            if ($result) {
                log_message('info', 'Inscripción creada correctamente con ID: ' . $result);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Inscripción realizada correctamente'
                ]);
            } else {
                $errors = $inscripcionModel->errors();
                log_message('error', 'Errores de validación: ' . json_encode($errors));
                return $this->response->setJSON([
                    'error' => 'Error de validación: ' . implode(', ', $errors)
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'EXCEPCIÓN: ' . $e->getMessage());
            return $this->response->setJSON([
                'error' => 'Error interno del servidor'
            ]);
        }
    }

    /**
     * Método: dashboard()
     * Propósito: Muestra el dashboard del estudiante con datos de la base de datos.
     * Nota: Si el estudiante está borrado lógicamente, redirige al login con error.
     * @return string La vista renderizada.
     */
    public function dashboard()
    {
        // ==================================================================
        // ¡SISTEMA DE LOGIN IMPLEMENTADO!
        // ==================================================================
        // Verificamos si el usuario ha iniciado sesión.
        // La sesión 'usuario' contiene el email del usuario logueado.
        // Buscamos el estudiante por email en lugar de asumir que el ID de usuario es el ID de estudiante.
        if (! $email_usuario = session()->get('usuario')) {
            return redirect()->to('/login')->with('error', 'Debe iniciar sesión para ver su dashboard.');
        }

        $estudianteModel = new EstudianteModel();
        $materiaModel = new MateriaModel();
        $inscripcionModel = new InscripcionModel();

        // Buscar el estudiante por email
        $estudiante = $estudianteModel->where('email', $email_usuario)->first();
        if (!$estudiante) {
            return redirect()->to('/login')->with('error', 'No se encontró un estudiante asociado a su cuenta.');
        }

        $id_est = $estudiante['id'];

        $data['estudiante'] = $estudianteModel->getEstudianteConCarrera($id_est);
        // Si el estudiante está borrado, redirige al login.
        if (!$data['estudiante']) {
            return redirect()->to('/login')->with('error', 'Su cuenta ha sido desactivada.');
        }

        // Obtenemos los datos una sola vez
        $notas = $estudianteModel->getNotas($id_est);
        $materias_inscritas = $estudianteModel->getMateriasInscritas($id_est);

        $data['notas'] = $notas;
        $data['materias_inscritas'] = $materias_inscritas;
        // Pasamos los datos al método para evitar consultas duplicadas
        $data['estadisticas'] = $estudianteModel->getEstadisticas($notas, $materias_inscritas);

        // NUEVO: Obtener todas las materias de la carrera para determinar estados dinámicos
        $todasMaterias = $materiaModel->where('carrera_id', $estudiante['carrera_id'])->findAll();

        // NUEVO: Para cada materia, determinar estado dinámico
        $materiasDisponibles = [];
        foreach ($todasMaterias as $materia) {
            $ultimaInscripcion = $inscripcionModel->getUltimaInscripcion($estudiante['id'], $materia['id']);

            if (!$ultimaInscripcion) {
                // Nunca se inscribió
                $materia['estado'] = 'inscribirme';
                $materia['boton_texto'] = 'Inscribirme';
                $materia['boton_clase'] = 'btn-primary';
                $materia['clickeable'] = true;
            } elseif ($ultimaInscripcion['estado_inscripcion'] == 'Reprobada') {
                // Reprobó anteriormente
                $materia['estado'] = 'no_curso';
                $materia['boton_texto'] = 'No la curso - Reintentar';
                $materia['boton_clase'] = 'btn-warning';
                $materia['clickeable'] = true;
            } elseif (in_array($ultimaInscripcion['estado_inscripcion'], ['Pendiente', 'Confirmada', 'Aprobada'])) {
                // Está cursando o aprobó
                $materia['estado'] = 'ya_inscrito';
                $materia['boton_texto'] = 'Ya inscrito';
                $materia['boton_clase'] = 'btn-secondary';
                $materia['clickeable'] = false;
            } else {
                // Otro estado
                $materia['estado'] = 'inscribirme';
                $materia['boton_texto'] = 'Inscribirme';
                $materia['boton_clase'] = 'btn-primary';
                $materia['clickeable'] = true;
            }

            $materiasDisponibles[] = $materia;
        }

        // NUEVO: Agregar al array data
        $data['materias_disponibles'] = $materiasDisponibles;

        // Preparamos arrays para los datos adicionales.
        $data['materiales_por_materia'] = [];
        $data['asistencias_por_materia'] = [];

        // Por cada materia en la que está inscripto, obtenemos sus datos.
        foreach ($data['materias_inscritas'] as $inscripcion) {
            $materia_id = $inscripcion['materia_id'];
            // ==================================================================
            // LÍNEA TEMPORALMENTE DESACTIVADA
            // Se comenta para evitar el error "Table 'material' doesn't exist".
            // Cuando crees la tabla 'material', puedes descomentar esta línea.
            // $data['materiales_por_materia'][$materia_id] = $materiaModel->getMateriales($materia_id);
            // Obtenemos las asistencias individuales.
            $asistencias_individuales = $estudianteModel->getAsistenciasIndividuales($inscripcion['id']);
            $data['asistencias_por_materia'][$materia_id] = $asistencias_individuales;
        }

        return view('Dashboard_Estudiantes/dashboard_estudiante', $data);
    }
}