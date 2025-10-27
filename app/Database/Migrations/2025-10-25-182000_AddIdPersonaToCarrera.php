<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdPersonaToCarrera extends Migration
{
    public function up()
    {
        $this->forge->addColumn('carrera', [
            'id_persona' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
                'after' => 'id_modalidad'
            ]
        ]);
        $this->forge->addForeignKey('id_persona', 'usuarios', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('carrera', 'carrera_id_persona_foreign');
        $this->forge->dropColumn('carrera', 'id_persona');
    }
}
