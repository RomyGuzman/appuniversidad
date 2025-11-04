<?php

namespace App\Models;
use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'Usuarios';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'usuario',
        'password',
        'rol_id',
        'activo'
    ];
}
