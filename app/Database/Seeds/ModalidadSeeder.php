<?php

// Define el espacio de nombres para organizar la clase dentro de la estructura del proyecto.
namespace App\Database\Seeds;

// Importa la clase base 'Seeder' de CodeIgniter, de la cual esta clase heredará.
use CodeIgniter\Database\Seeder;

// Define la clase 'ModalidadSeeder' que se encargará de poblar la tabla 'Modalidad'.
class ModalidadSeeder extends Seeder
{
    /**
     * El método run() es el que se ejecuta cuando se llama a este seeder.
     * Contiene toda la lógica para insertar los datos iniciales.
     */
    public function run()
    {
        // Crea una instancia del Query Builder de CodeIgniter, apuntando a la tabla 'Modalidad'.
        // Esto nos permite construir consultas de base de datos de forma segura y sencilla.
        $builder = $this->db->table('Modalidad');

        // Define un array con los datos de las modalidades que se van a insertar.
        // Cada elemento del array es una fila de la tabla.
        $modalidades = [
            ['codigo_modalidad'=>'MOD-PRES', 'nombre_modalidad'=>'PRESENCIAL', 'carrera_id' => 1],
            ['codigo_modalidad'=>'MOD-SEMI', 'nombre_modalidad'=>'SEMIPRESENCIAL', 'carrera_id' => 1],
            ['codigo_modalidad'=>'MOD-VIRT', 'nombre_modalidad'=>'VIRTUAL', 'carrera_id' => 2],
            ['codigo_modalidad'=>'MOD-LIB', 'nombre_modalidad'=>'LIBRE', 'carrera_id' => 2],
        ];

        // Ejecuta un comando TRUNCATE en la tabla 'Modalidad'.
        // Esto la vacía por completo, eliminando todos los registros existentes.
        // Es una buena práctica para asegurar que el seeder se pueda ejecutar múltiples veces sin crear datos duplicados.
        $builder->truncate();

        // Inserta todos los datos del array '$modalidades' en la tabla 'Modalidad'.
        // 'insertBatch' es un método muy eficiente que inserta múltiples filas en una sola consulta.
        $builder->insertBatch($modalidades);
    }
}