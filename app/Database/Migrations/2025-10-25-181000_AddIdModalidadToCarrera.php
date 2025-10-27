<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdModalidadToCarrera extends Migration
{
    public function up()
    {
        $this->forge->addColumn('carrera', [
            'id_modalidad' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
                'after' => 'duracion'
            ]
        ]);
        $this->forge->addForeignKey('id_modalidad', 'modalidad', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('carrera', 'carrera_id_modalidad_foreign');
        $this->forge->dropColumn('carrera', 'id_modalidad');
    }
}
