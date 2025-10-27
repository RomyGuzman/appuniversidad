<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoriaIdToCarrera extends Migration
{
    public function up()
    {
        $this->forge->addColumn('carrera', [
            'categoria_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'codigo_carrera'
            ]
        ]);

        // Agregar clave foránea
        $this->forge->addForeignKey(
            'categoria_id',
            'categoria',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->forge->dropForeignKey('carrera', 'carrera_categoria_id_foreign');
        $this->forge->dropColumn('carrera', 'categoria_id');
    }
}
