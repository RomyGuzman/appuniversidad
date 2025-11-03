<?php

// Define el espacio de nombres para organizar la clase dentro de la estructura del proyecto.
namespace App\Database\Seeds;

// Importa la clase base 'Seeder' de CodeIgniter, de la cual esta clase heredará.
use CodeIgniter\Database\Seeder;

// Define la clase 'CategoriaSeeder' que se encargará de poblar la tabla 'Categoria'.
class CategoriaSeeder extends Seeder
{
    /**
     * El método run() es el que se ejecuta cuando se llama a este seeder.
     * Contiene toda la lógica para insertar los datos iniciales de las categorías.
     */
    public function run()
    {
        // Crea una instancia del Query Builder de CodeIgniter, apuntando a la tabla 'Categoria'.
        // Esto nos permite construir consultas de base de datos de forma segura y sencilla.
        $builder = $this->db->table('Categoria');

        // Define un array con los datos de las categorías que se van a insertar.
        // Cada elemento del array es una fila de la tabla.
        $categorias = [
            ['codigo_categoria'=>'CAT-TEC', 'nombre_categoria'=>'TECNICATURas SUPERIORES', 'carrera_id' => 1],
            ['codigo_categoria'=>'CAT-PROF', 'nombre_categoria'=>'PROFESORADOS', 'carrera_id' => 2],
            ['codigo_categoria'=>'CAT-EDUC', 'nombre_categoria'=>'EDUCACIÓN', 'carrera_id' => 2],
            ['codigo_categoria'=>'CAT-SALUD', 'nombre_categoria'=>'SALUD', 'carrera_id' => 1],
            ['codigo_categoria'=>'CAT-TECNO', 'nombre_categoria'=>'TECNOLOGÍA', 'carrera_id' => 1],
        ];

        // Ejecuta un comando TRUNCATE en la tabla 'Categoria'.
        // Esto la vacía por completo, eliminando todos los registros existentes.
        // Es una buena práctica para asegurar que el seeder se pueda ejecutar múltiples veces sin crear datos duplicados.
        $builder->truncate();

        // Inserta todos los datos del array '$categorias' en la tabla 'Categoria'.
        // 'insertBatch' es un método muy eficiente que inserta múltiples filas en una sola consulta.
        $builder->insertBatch($categorias);
    }
}