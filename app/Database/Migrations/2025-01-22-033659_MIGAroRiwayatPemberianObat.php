<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MIGAroRiwayatPemberianObat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jam' => [
                'type' => 'TIME',
            ],
            'no_rawat' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'label_jam_pemberian' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        $this->forge->addKey(['tanggal', 'jam', 'no_rawat', 'kode_barang'], true);

        $this->forge->createTable('aro_riwayat_pemberian_obat');
    }

    public function down()
    {
        $this->forge->dropTable('aro_riwayat_pemberian_obat');
    }
}
