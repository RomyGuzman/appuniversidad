<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InscripcionSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'estudiante_id' => 1,
                'materia_id' => 1,
                'fecha_inscripcion' => '2025-11-09',
                'estado_inscripcion' => 'Pendiente',
                'observaciones_inscripcion' => 'Inscripción de prueba desde seeder'
            ]
        ];

        $this->db->table('inscripcion')->insertBatch($data);
    }
}
