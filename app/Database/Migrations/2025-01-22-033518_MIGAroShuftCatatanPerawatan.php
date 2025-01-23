<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MIGAroShuftCatatanPerawatan extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'no_rawat' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jam' => [
                'type' => 'TIME',
            ],
            'shift' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
        ]);

        // Set the composite primary key
        $this->forge->addKey(['no_rawat', 'tanggal', 'jam', 'shift'], true);
        
        $this->forge->createTable('aro_shift_catatan_perawatan');
    }

    public function down()
    {
        //
        $this->forge->dropTable('aro_shift_catatan_perawatan');
    }
}
