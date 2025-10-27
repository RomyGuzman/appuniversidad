<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MarkMigrationsApplied extends Seeder
{
    public function run()
    {
        $data = [
            [
                'version' => '2025-09-27-010101',
                'class' => 'CreateTablaCategoria',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-27-010102',
                'class' => 'CreateTablaModalidad',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-27-010103',
                'class' => 'CreateTablaCarrera',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-27-010104',
                'class' => 'CreateTablaProfesor',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-27-010105',
                'class' => 'CreateTablaAlumno',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-27-010106',
                'class' => 'CreateTablaInscripcion',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-28-000001',
                'class' => 'CreateTablaRol',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ],
            [
                'version' => '2025-09-28-000002',
                'class' => 'CreateTablaUsuarios',
                'namespace' => 'App',
                'time' => time(),
                'batch' => 1
            ]
        ];

        $this->db->table('migrations')->insertBatch($data);
    }
}
