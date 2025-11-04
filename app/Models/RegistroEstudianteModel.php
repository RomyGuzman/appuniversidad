<?php

namespace App\Models;
use CodeIgniter\Model;

class RegistroEstudianteModel extends Model
{
    protected $table = 'Estudiante';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'dni',
        'nombre_estudiante',
        'fecha_nacimiento',
        'edad',
        'email',
        'carrera_id'
    ];
}
