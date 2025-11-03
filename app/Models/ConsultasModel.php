<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsultaAdminModel extends Model
{
    protected $table = 'consultas_admin';
    protected $primaryKey = 'id_consulta';
    protected $allowedFields = ['email_usuario', 'mensaje', 'estado', 'asunto'];
    protected $useTimestamps = false; // Usamos fecha_creacion con DEFAULT CURRENT_TIMESTAMP en la tabla
    protected $createdField = 'fecha_creacion'; // Aunque no lo usamos manualmente, por si acaso
}