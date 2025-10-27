<?php

namespace App\Controllers;
use App\Controllers\BaseController; // <-- CAMBIO: Importar BaseController
// Importa el modelo necesario.
use App\Models\CategoriaModel;
/**
 * Gestiona todas las operaciones CRUD (Crear, Leer, Actualizar, Borrar)
 * para las categorías de las carreras. Su estructura es muy similar a la
 * de otros controladores del sistema.
 */
class Categorias extends BaseController
{
    /**
     * Propósito: Muestra la página principal de gestión de categorías.
     * Tareas:
     * 1. Carga el modelo de Categorías.
     * 2. Obtiene todas las categorías de la base de datos.
     * 3. Pasa los datos a la vista 'categorias.php'.
     * @return string La vista renderizada.
     */
    public function index()
    {
        // Para evitar error de conexión a base de datos, mostramos la vista con datos vacíos si no hay conexión.
        try {
            $model = new CategoriaModel();
            $data['categorias'] = $model->findAll();
        } catch (\Exception $e) {
            // Si hay error, mostramos la vista con datos vacíos.
            $data['categorias'] = [];
        }
        // Carga la vista y le pasa los datos.
        return view('administrador/categorias', $data);
    }

    /**
     * Propósito: Procesa el envío del formulario para crear una nueva categoría.
     * Tareas:
     * 1. Recoge los datos del formulario (nombre y código).
     * 2. Usa el método `save()` del modelo para validar y guardar los datos.
     * 3. Redirige con un mensaje de éxito o con los errores de validación.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function registrar()
    {
        $model = new CategoriaModel();
        // Recoge los datos del formulario.
        $data = [
            'ncat' => $this->request->getPost('ncat'),
            'codcat' => $this->request->getPost('codcat')
        ];

        // Intenta guardar los datos. El modelo se encarga de la validación.
        if ($model->save($data) === false) {
            return redirect()->to('/categorias')->withInput()->with('errors', 'Error al registrar: ' . implode(', ', $model->errors()));
        }

        // Si es exitoso, redirige con un mensaje de éxito.
        return redirect()->to('/categorias')->with('success', 'Categoría registrada correctamente.');
    }

    /**
     * Método: edit($id)
     * Propósito: Obtiene los datos de una categoría para el modal de edición.
     * Tareas:
     * 1. Responde a una petición AJAX.
     * 2. Busca la categoría por su ID.
     * 3. Devuelve los datos en formato JSON.
     * @param int $id El ID de la categoría.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function edit($id)
    {
        $model = new CategoriaModel();
        $categoria = $model->find($id);

        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            if ($categoria) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($categoria);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Categoría no encontrada']);
            }
        }
    }

    /**
     * Método: update($id)
     * Propósito: Procesa el envío del formulario de edición para actualizar una categoría.
     * Tareas:
     * 1. Recoge los datos del formulario modal.
     * 2. Llama al método `update()` del modelo para validar y guardar los cambios.
     * @param int $id El ID de la categoría a actualizar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $model = new CategoriaModel();
        // Recoge explícitamente solo los campos necesarios para evitar conflictos.
        $data = [
            'ncat' => $this->request->getPost('ncat'),
            'codcat' => $this->request->getPost('codcat')
        ];

        // Intenta actualizar los datos.
        if ($model->update($id, $data) === false) {
            return redirect()->to('/categorias')->withInput()->with('errors', 'Error al actualizar: ' . implode(', ', $model->errors()));
        }

        // Si es exitoso, redirige con un mensaje de éxito.
        return redirect()->to('/categorias')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Método: delete($id)
     * Propósito: Elimina una categoría.
     * Tareas:
     * 1. Llama al método `delete()` del modelo con el ID correspondiente.
     * @param int $id El ID de la categoría a eliminar.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $model = new CategoriaModel();
        // Intenta eliminar el registro.
        if ($model->delete($id)) {
            return redirect()->to('/categorias')->with('success', 'Categoría eliminada correctamente.');
        } else {
            // Si falla, redirige con un mensaje de error.
            return redirect()->to('/categorias')->with('error', 'No se pudo eliminar la categoría.');
        }
    }

    /**
     * Método: search($id)
     * Propósito: Busca una categoría por su ID para mostrar sus detalles.
     * Tareas:
     * 1. Responde a una petición AJAX desde el formulario "Buscar por ID".
     * 2. Devuelve los datos de la categoría encontrada en formato JSON.
     * @param int $id El ID de la categoría a buscar.
     * @return \CodeIgniter\HTTP\ResponseInterface|void
     */
    public function search($id)
    {
        // Verifica si la petición es de tipo AJAX.
        if ($this->request->isAJAX()) {
            $model = new CategoriaModel();
            $categoria = $model->find($id);

            if ($categoria) {
                // Si se encuentra, devuelve los datos en formato JSON.
                return $this->response->setJSON($categoria);
            } else {
                // Si no, devuelve un error 404.
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Categoría no encontrada con ese ID.']);
            }
        }
    }
}