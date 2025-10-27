<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDuracionToCarrera extends Migration
{
    public function up()
    {
        $this->forge->addColumn('carrera', [
            'duracion' => [
                'type' => 'INT',
                'null' => true,
                'after' => 'categoria_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('carrera', 'duracion');
    }
}
