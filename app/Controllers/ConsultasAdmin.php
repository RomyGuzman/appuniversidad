<?php

namespace App\Controllers;
use App\Models\ConsultaAdminModel;

class ConsultasAdmin extends BaseController
{
    /**
     * Muestra la página principal con la lista de todas las consultas.
     */
    public function index()
    {
        $consultaModel = new ConsultaAdminModel();
        // Usamos el nuevo método para obtener datos paginados y con información del remitente
        $data['consultas'] = $consultaModel->getConsultasPaginadas(15); // Mostrar 15 por página
        $data['pager'] = \Config\Services::pager(); // Pasamos el paginador a la vista

        return view('administrador/alertas', $data);
    }

    /**
     * Devuelve el número de consultas pendientes.
     * Diseñado para ser llamado vía AJAX.
     */
    public function getUnreadCount()
    {
        if ($this->request->isAJAX()) {
            $consultaModel = new ConsultaAdminModel();
            $count = $consultaModel->where('estado', 'pendiente')->countAllResults();
            return $this->response->setJSON(['unread_count' => $count]);
        }
        // Si no es AJAX, no hacemos nada.
        return $this->response->setStatusCode(403);
    }

    /**
     * Marca una consulta como 'resuelta'.
     * Diseñado para ser llamado vía AJAX.
     * @param int $id El ID de la consulta a actualizar.
     */
    public function markAsRead($id = null)
    {
        if ($this->request->isAJAX() && $id) {
            $consultaModel = new ConsultaAdminModel();
            $consulta = $consultaModel->find($id);

            if ($consulta && $consultaModel->update($id, ['estado' => 'resuelta'])) {
                // Devuelve una respuesta exitosa. El JS se encargará de actualizar el contador.
                return $this->response->setJSON(['success' => true, 'message' => 'Consulta marcada como resuelta.']);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'No se pudo actualizar o encontrar la consulta.']);
        }

        return $this->response->setStatusCode(403);
    }
}
