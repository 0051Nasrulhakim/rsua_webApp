<?php

namespace App\Models;

use CodeIgniter\Model;

class AroRiwayatPemberianObat extends Model
{
    protected $table            = 'aro_riwayat_pemberian_obat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tanggal',
        'jam',
        'no_rawat',
        'kode_barang',
        'label_jam_pemberian'
    ];

}
