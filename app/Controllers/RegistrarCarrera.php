<?php

// Define el espacio de nombres para la sección de Administración.
namespace App\Controllers;
use App\Controllers\BaseController;
// Importa los modelos necesarios.
use App\Models\CarreraModel;
use App\Models\CategoriaModel;
use App\Models\ModalidadModel;
use App\Models\UsuarioModel; // CORRECCIÓN: El modelo correcto es UsuarioModel
/**
 * (Nombrado 'RegistrarCarrera' pero gestiona todo el CRUD de Carreras).
 * Maneja todas las operaciones para las carreras ofrecidas por el instituto.
 */
class RegistrarCarrera extends BaseController
{
    /**
     * Método: index()
     * Propósito: Muestra la página principal de gestión de carreras.
     * Tareas:
     * 1. Carga los modelos de Carrera, Categoría y Modalidad.
     * 2. Obtiene todas las carreras para mostrarlas en la tabla.
     * 3. Obtiene todas las categorías y modalidades para rellenar los menús
     *    desplegables, y las personas para el campo de director.
     * 4. Pasa todos los datos a la vista 'registrarCarrera.php' para que los muestre.
     * @return string La vista renderizada.
     */
    public function index()
    {
        // Para evitar error de conexión a base de datos, mostramos la vista con datos vacíos si no hay conexión.
        try {
            // Instancia los modelos.
            $carreraModel = new CarreraModel();
            $categoriaModel = new CategoriaModel();
            $modalidadModel = new ModalidadModel();
            $usuarioModel = new UsuarioModel();

            // Prepara el array '$data' para la vista.
            // MEJORA: Usamos el método que trae los nombres de categoría y modalidad en una sola consulta.
            $data['carreras'] = $carreraModel->getCarrerasCompletas();
            $data['categorias'] = $categoriaModel->findAll();
            $data['modalidades'] = $modalidadModel->findAll();
            $data['personas'] = $usuarioModel->findAll(); // CORRECCIÓN: Usamos el modelo correcto
        } catch (\Exception $e) {
            // Si hay un error (ej: conexión a BD), preparamos los arrays vacíos para evitar errores en la vista.
            $data['carreras'] = [];
            $data['categorias'] = [];
            $data['modalidades'] = [];
            $data['personas'] = [];
        }

        // Carga la vista y le pasa los datos.
        return view('administrador/registrarCarrera', $data);
    }

    /**
     * Método: registrar()
     * Propósito: Procesa el envío del formulario para crear una nueva carrera.
     * Tareas:
     * 1. Recoge todos los datos del formulario de registro de carrera.
     * 2. Genera un código único para la carrera basado en su nombre.
     * 3. Usa el método `save()` del modelo para validar y guardar los datos.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function registrar()
    {
        $model = new CarreraModel();
        $nombreCarrera = $this->request->getPost('nombre_carrera');

        // Recoge los datos del formulario.
        $data = [
            'nombre_carrera' => $nombreCarrera,
            // Llama al método privado para generar un código único (ej: "DS-1").
            'codigo_carrera' => $this->generarCodigoCarrera($nombreCarrera ?? ''),
            'categoria_id' => $this->request->getPost('id_categoria'),
            'modalidad_id' => $this->request->getPost('id_modalidad'),

        ];

        // Intenta guardar los datos. El modelo se encarga de la validación.
        if ($model->save($data) === false) {
            // --- CORRECCIÓN ---
            // Si la validación falla, no solo redirigimos. También debemos recargar
            // los datos para los desplegables (categorías, modalidades, personas)
            // para que no aparezcan vacíos al mostrar los errores.
            $categoriaModel = new CategoriaModel();
            $modalidadModel = new ModalidadModel();
            $usuarioModel = new UsuarioModel();

            // Preparamos los datos para la vista, igual que en el método index().
            $viewData['carreras'] = $model->getCarrerasCompletas();
            $viewData['categorias'] = $categoriaModel->findAll();
            $viewData['modalidades'] = $modalidadModel->findAll();
            $viewData['personas'] = $usuarioModel->findAll();
            $viewData['errors'] = $model->errors(); // Añadimos los errores de validación.

            // Volvemos a mostrar la vista principal, pero esta vez con los errores.
            return view('administrador/registrarCarrera', $viewData);
        }

        // Si es exitoso, redirige a la lista de carreras con un mensaje de éxito.
        return redirect()->to('/carreras')->with('success', 'Carrera registrada correctamente.');
    }

    /**
     * Método: edit($id)
     * Propósito: Obtiene los datos de una carrera para el modal de edición.
     * Tareas:
     * 1. Responde a una petición AJAX.
     * 2. Busca la carrera por su ID y devuelve los datos en formato JSON.
     * @param int $id El ID de la carrera.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function edit($id)
    {
        $model = new CarreraModel();
        $carrera = $model->find($id);

        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            if ($carrera) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($carrera);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Carrera no encontrada']);
            }
        }
    }

    /**
     * Método: update($id)
     * Propósito: Procesa el envío del formulario de edición para actualizar una carrera.
     * Tareas:
     * 1. Llama al método `update()` del modelo para validar y guardar los cambios.
     * @param int $id El ID de la carrera a actualizar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $model = new CarreraModel();
        // Es una mejor práctica definir explícitamente los campos que se pueden actualizar.
        // Esto previene ataques de "Mass Assignment" donde un usuario podría intentar
        // modificar campos no deseados (como 'codigo_carrera').
        $data = [
            'nombre_carrera' => $this->request->getPost('nombre_carrera'),
            'categoria_id' => $this->request->getPost('id_categoria'),
            'modalidad_id' => $this->request->getPost('id_modalidad'),
        ];

        // Intenta actualizar los datos. El modelo se encarga de la validación.
        if ($model->update($id, $data) === false) {
            // --- CORRECCIÓN ---
            // Si la validación de la actualización falla, recargamos los datos
            // para los desplegables para que no aparezcan vacíos al mostrar los errores.
            $categoriaModel = new CategoriaModel();
            $modalidadModel = new ModalidadModel();
            $usuarioModel = new UsuarioModel();

            $viewData['carreras'] = $model->getCarrerasCompletas();
            $viewData['categorias'] = $categoriaModel->findAll();
            $viewData['modalidades'] = $modalidadModel->findAll();
            $viewData['personas'] = $usuarioModel->findAll();
            $viewData['errors'] = $model->errors(); // Añadimos los errores de validación.

            return view('administrador/registrarCarrera', $viewData);
        }

        // Si es exitoso, redirige con un mensaje de éxito.
        return redirect()->to('/carreras')->with('success', 'Carrera actualizada correctamente.');
    }

    /**
     * Método: delete($id)
     * Propósito: Elimina una carrera.
     * Tareas:
     * 1. Llama al método `delete()` del modelo con el ID correspondiente.
     * @param int $id El ID de la carrera a eliminar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $model = new CarreraModel();
        // Intenta eliminar el registro.
        if ($model->delete($id)) {
            return redirect()->to('/carreras')->with('success', 'Carrera eliminada correctamente.');
        } else {
            // Si falla, redirige con un mensaje de error.
            return redirect()->to('/carreras')->with('error', 'No se pudo eliminar la carrera.');
        }
    }

    /**
     * Método: search($id)
     * Propósito: Busca una carrera por su ID para mostrar sus detalles.
     * Tareas:
     * 1. Responde a una petición AJAX.
     * 2. Usa un método personalizado del modelo para obtener la carrera y el nombre de su categoría.
     * @param int $id El ID de la carrera a buscar.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function search($id)
    {
        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            $model = new CarreraModel();
            // Llama a un método personalizado del modelo que une las tablas.
            $carrera = $model->getCarreraCompleta($id); // Asumiendo que el método se llama así

            if ($carrera) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($carrera);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Carrera no encontrada con ese ID.']);
            }
        }
    }

    /**
     * Genera un código único para una carrera basado en su nombre.
     * Ejemplo: "Desarrollo de Software" -> "DS-1", "DS-2", etc.
     * @param ?string $nombreCarrera El nombre de la carrera.
     * @return string El código generado.
     */
    private function generarCodigoCarrera(?string $nombreCarrera = ''): string
    {
        $model = new CarreraModel();

        // Asegurar que sea string
        $nombreCarrera = (string) $nombreCarrera;

        // Paso 1: Crear un acrónimo a partir de las iniciales del nombre de la carrera.
        $palabras = explode(' ', $nombreCarrera);
        $acronimo = '';
        // Limita a un máximo de 4 iniciales para evitar códigos demasiado largos.
        $palabras = array_slice($palabras, 0, 4);

        foreach ($palabras as $palabra) {
            if (!empty($palabra)) {
                // Concatena la primera letra de cada palabra, en mayúsculas.
                $acronimo .= strtoupper(substr($palabra, 0, 1));
            }
        }

        // Paso 2: Buscar en la base de datos cuántas carreras ya existen con ese mismo acrónimo.
        // Por ejemplo, si el acrónimo es "DS", busca códigos como "DS-1", "DS-2", etc.
        $existentes = $model->like('codigo_carrera', $acronimo . '-', 'after')->countAllResults();

        // Paso 3: Devolver el nuevo código con el número secuencial siguiente.
        $siguienteNumero = $existentes + 1;
        return $acronimo . '-' . $siguienteNumero;
    }

    /**
     * Genera un código de carrera y lo devuelve como JSON para una petición AJAX.
     * Este método es el punto de entrada para la funcionalidad de "código en tiempo real".
     * @param string $nombreCarrera El nombre de la carrera (codificado para URL).
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function generarCodigoAjax($nombreCarrera = '')
    {
        // Verifica que sea una petición AJAX y que el nombre no esté vacío.
        if ($this->request->isAJAX() && !empty($nombreCarrera)) {
            // Decodifica el nombre de la carrera que viene de la URL (ej: %20 se convierte en espacio).
            $nombreDecodificado = urldecode($nombreCarrera);
            // Llama a la función privada para generar el código.
            $codigo = $this->generarCodigoCarrera($nombreDecodificado);
            // Devuelve el código generado en formato JSON.
            return $this->response->setJSON(['codigo' => $codigo]);
        }
        // Si no es una petición AJAX, devuelve un error 403 (Prohibido).
        return $this->response->setStatusCode(403);
    }
}