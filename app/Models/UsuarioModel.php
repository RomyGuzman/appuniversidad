<?php

namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'usuario',
        'password',
        'rol_id',
        'activo',
        'fecha_ultimo_ingreso'
    ];
}
