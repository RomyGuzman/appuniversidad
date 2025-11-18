<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Models;
// Importa la clase base 'Model' de CodeIgniter.
use CodeIgniter\Model;

// Define la clase 'CarreraModel' que hereda de la clase Model de CodeIgniter.
// Esta clase se encarga de toda la interacción con la tabla 'Carrera'.
class CarreraModel extends Model
{
    // --- Propiedades de Configuración del Modelo ---

    // Especifica la tabla de la base de datos que este modelo representa.
    protected $table = 'carrera';
    // Especifica el nombre de la columna que es la clave primaria de la tabla.
    protected $primaryKey = 'id';
    // Define los campos de la tabla que se pueden insertar o actualizar masivamente.
    // Es una medida de seguridad para prevenir ataques de "Mass Assignment".
    protected $allowedFields = ['nombre_carrera', 'codigo_carrera', 'categoria_id', 'modalidad_id', 'duracion'];
    // Desactiva los campos de timestamp automáticos ('created_at', 'updated_at').
    protected $useTimestamps = false;

    // Define las reglas de validación que se aplicarán automáticamente antes de
    // cualquier operación de inserción (save) o actualización (update).
    protected $validationRules = [
        'id' => 'permit_empty|integer',
        // 'nombre_carrera' es obligatorio.
        'nombre_carrera' => 'required|min_length[2]|max_length[120]',
        // 'codigo_carrera' es obligatorio y único.
        'codigo_carrera' => 'required|min_length[2]|max_length[20]|is_unique[carrera.codigo_carrera,id,{id}]',
        // 'duracion' no es obligatorio, pero si se proporciona, debe ser un número natural mayor que cero y menor o igual a 12.
        'duracion'  => 'permit_empty|is_natural_no_zero|less_than_equal_to[12]', // CORREGIDO: La regla es less_than_equal_to
        // 'modalidad_id' e 'categoria_id' no son obligatorios, pero deben ser enteros si se proporcionan.
        'modalidad_id'    => 'permit_empty|integer',
        'categoria_id'    => 'permit_empty|integer',

    ];
    // Define mensajes de error personalizados para las reglas de validación.
    protected $validationMessages = [
        'codigo_carrera' => [
            'is_unique' => 'El código de carrera ya existe.'
        ],
    ];

    // --- Métodos Personalizados ---

    /**
     * Obtiene todas las carreras con el nombre de su categoría y modalidad.
     * Este método es útil para mostrar información completa en las vistas.
     * @return array
     */
    public function getCarrerasCompletas()
    {
        return $this->select('carrera.*, categoria.nombre_categoria, modalidad.nombre_modalidad')
                    ->join('categoria', 'categoria.id = carrera.categoria_id', 'left')
                    ->join('modalidad', 'modalidad.id = carrera.modalidad_id', 'left')
                    ->findAll();
    }

    /**
     * Obtiene una carrera específica con el nombre de su categoría y modalidad.
     * @param int $id El ID de la carrera.
     * @return array|object|null
     */
    public function getCarreraCompleta($id)
    {
        // Obtiene la carrera con sus campos categoria_id y modalidad_id
        $carrera = $this->find($id);

        if ($carrera) {
            // Si tiene categoria_id, busca el nombre en la tabla categoria
            if (!empty($carrera['categoria_id'])) {
                $categoriaModel = new \App\Models\CategoriaModel();
                $categoria = $categoriaModel->find($carrera['categoria_id']);
                $carrera['nombre_categoria'] = $categoria ? $categoria['nombre_categoria'] : 'No encontrada';
            } else {
                $carrera['nombre_categoria'] = 'No asignada';
            }

            // Si tiene modalidad_id, busca el nombre en la tabla modalidad
            if (!empty($carrera['modalidad_id'])) {
                $modalidadModel = new \App\Models\ModalidadModel();
                $modalidad = $modalidadModel->find($carrera['modalidad_id']);
                $carrera['nombre_modalidad'] = $modalidad ? $modalidad['nombre_modalidad'] : 'No encontrada';
            } else {
                $carrera['nombre_modalidad'] = 'No asignada';
            }
        }

        return $carrera;
    }
}