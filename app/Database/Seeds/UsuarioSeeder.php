<?php

// Define el espacio de nombres para organizar la clase.
namespace App\Database\Seeds;

// Importa la clase base 'Seeder' de CodeIgniter.
use CodeIgniter\Database\Seeder;

// Define la clase 'UsuarioSeeder' para poblar la tabla 'usuarios'.
class UsuarioSeeder extends Seeder
{
    /**
     * El método run() se ejecuta cuando se llama a este seeder.
     * Contiene la lógica para insertar los tipos de usuario iniciales.
     */
    public function run()
    {
        // Crea una instancia del Query Builder apuntando a la tabla 'usuarios'.
        $builder = $this->db->table('usuarios');
        // Vacía la tabla 'usuarios' para evitar duplicados.
        $builder->truncate();

        // Define un array con los usuarios de ejemplo.
        $usuarios = [
            [
                'usuario' => 'admin',
                'password' => md5('123456'),
                'fecha_registro' => date('Y-m-d H:i:s'),
                'rol_id' => 1,
                'activo' => 1,
            ],
            [
                'usuario' => 'profesor1',
                'password' => md5('123456'),
                'fecha_registro' => date('Y-m-d H:i:s'),
                'rol_id' => 2,
                'activo' => 1,
            ],
            [
                'usuario' => 'alumno1',
                'password' => md5('123456'),
                'fecha_registro' => date('Y-m-d H:i:s'),
                'rol_id' => 3,
                'activo' => 1,
            ],
        ];

        // Inserta el lote de usuarios en la base de datos.
        $builder->insertBatch($usuarios);
    }
}