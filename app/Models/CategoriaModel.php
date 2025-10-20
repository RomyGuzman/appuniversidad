<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Models;
// Importa la clase base 'Model' de CodeIgniter.
use CodeIgniter\Model;

// Define la clase 'CategoriaModel' que se encarga de toda la interacción con la tabla 'Categoria'.
class CategoriaModel extends Model
{
    // --- Propiedades de Configuración del Modelo ---

    // Especifica la tabla de la base de datos que este modelo representa.
    protected $table      = 'Categoria';
    // Especifica el nombre de la columna que es la clave primaria.
    protected $primaryKey = 'id';
    // Define los campos que se pueden insertar o actualizar masivamente.
    protected $allowedFields = ['id', 'codigo_categoria','nombre_categoria'];
    // Desactiva los campos de timestamp automáticos ('created_at', 'updated_at').
    protected $useTimestamps = false;

    // Define las reglas de validación que se aplicarán antes de guardar o actualizar.
    protected $validationRules = [
        // 'id' no es requerido para la creación.
        'id' => 'permit_empty|integer', // Cambiado a permit_empty para permitir la creación
        // 'codigo_categoria' es obligatorio, único en la tabla (ignorando el registro actual en una actualización), y con un máximo de 20 caracteres.
        'codigo_categoria' => 'required|is_unique[Categoria.codigo_categoria,id,{id}]|max_length[20]',
        // 'nombre_categoria' es obligatorio, con una longitud entre 2 y 120 caracteres.
        'nombre_categoria'   => 'required|min_length[2]|max_length[120]',
    ];
    // Define mensajes de error personalizados para las reglas de validación.
    protected $validationMessages = [
        'codigo_categoria' => [
            'is_unique'=>'El código de categoría ya existe.'
        ]
    ];
}