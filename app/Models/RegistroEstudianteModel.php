<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistroEstudianteModel extends Model
{
    protected $table = 'Estudiante';
    protected $primaryKey = 'id';
    protected $allowedFields = ['dni', 'nombre_estudiante', 'fecha_nacimiento', 'edad', 'email', 'carrera_id', 'modalidad_id', 'categoria_id'];
    protected $useTimestamps = false;

    // Método para registrar estudiante y usuario
    public function registrarEstudiante($data)
    {
        // Calcular edad a partir de fecha_nacimiento
        $fechaNacimiento = new \DateTime($data['fecha_nacimiento']);
        $hoy = new \DateTime();
        $edad = $hoy->diff($fechaNacimiento)->y;
        $data['edad'] = str_pad($edad, 2, '0', STR_PAD_LEFT); // Formato CHAR(2)

        // Insertar en Estudiante
        $estudianteId = $this->insert($data);

        if ($estudianteId) {
            // Obtener ID del rol 'estudiante'
            $rolModel = new RolModel();
            $rol = $rolModel->where('nombre_rol', 'estudiante')->first();
            if (!$rol) {
                throw new \Exception('Rol de estudiante no encontrado.');
            }

            // Insertar en Usuarios
            $usuarioModel = new UsuarioModel();
            $usuarioData = [
                'usuario' => $data['email'],
                'password' => password_hash($data['dni'], PASSWORD_DEFAULT),
                'rol_id' => $rol['id'],
                'activo' => true
            ];
            $usuarioModel->insert($usuarioData);
        }

        return $estudianteId;
    }
}

// Modelos auxiliares (si no existen, créalos)
class RolModel extends Model
{
    protected $table = 'Rol';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_rol'];
}

class UsuarioModel extends Model
{
    protected $table = 'Usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario', 'password', 'rol_id', 'activo'];
}

class CarreraModel extends Model
{
    protected $table = 'Carrera';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre_carrera', 'codigo_carrera'];
}

class ModalidadModel extends Model
{
    protected $table = 'Modalidad';
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo_modalidad', 'nombre_modalidad', 'carrera_id'];
}

class CategoriaModel extends Model
{
    protected $table = 'Categoria';
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo_categoria', 'nombre_categoria', 'carrera_id'];
}