<?php

namespace App\Models;
use CodeIgniter\Model;

class ConsultaAdminModel extends Model
{
    protected $table = 'consultas_admin';
    protected $primaryKey = 'id_consulta';
    protected $allowedFields = ['email_usuario', 'mensaje', 'estado', 'asunto'];
    
    /**
     * Obtiene las consultas paginadas con la información del remitente.
     * Esta es la forma más eficiente de obtener los datos.
     */
    public function getConsultasPaginadas(int $perPage = 10)
    {
        $builder = $this->db->table('consultas_admin c');
        $builder->select('
            c.id_consulta, 
            c.asunto, 
            c.mensaje, 
            c.estado, 
            c.fecha_creacion, 
            c.email_usuario,
            r.nombre_rol,
            COALESCE(e.nombre_estudiante, p.nombre_profesor) as nombre_remitente
        ');

        $builder->join('roles r', 'r.id = c.rol_id', 'left');
        $builder->join('estudiante e', 'e.id = c.usuario_id AND c.rol_id = 3', 'left');
        $builder->join('profesores p', 'p.id = c.usuario_id AND c.rol_id = 2', 'left');

        $builder->orderBy('c.estado', 'ASC');
        $builder->orderBy('c.fecha_creacion', 'DESC');

        return $builder->get($perPage)->getResult();
    }
}
