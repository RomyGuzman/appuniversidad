<?php

namespace App\Models;

use CodeIgniter\Model;

class ModalidadModel extends Model
{
    protected $table = 'modalidad'; // Corregido a minúsculas por convención
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo_modalidad', 'nombre_modalidad'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'codigo_modalidad' => 'required|min_length[2]|max_length[20]|is_unique[modalidad.codigo_modalidad,id,{id}]',
        'nombre_modalidad' => 'required|min_length[2]|max_length[120]',
    ];

    protected $validationMessages = [
        'codigo_modalidad' => [
            'is_unique' => 'El código de modalidad ya existe.',
        ],
    ];
}
