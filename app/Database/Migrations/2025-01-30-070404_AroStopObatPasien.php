<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AroStopObatPasien extends Migration
{
    public function up()
    {
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
            'kode_brng' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'shift'=> [
                'type'=> 'VARCHAR',
                'constraint'=> 10,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'endStop' => [
                'type' => 'TEXT'
            ]
        ]);


        $this->forge->addKey(['no_rawat', 'tanggal', 'jam', 'shift', 'kode_brng'], true);
        
        $this->forge->createTable('aro_stop_obat_pasien');
    }

    public function down()
    {
        //
        $this->forge->dropTable('aro_stop_obat_pasien');
    }
}
