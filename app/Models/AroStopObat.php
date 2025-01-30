<?php

namespace App\Models;

use CodeIgniter\Model;

class AroStopObat extends Model
{
    protected $table            = 'aro_stop_obat_pasien';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_rawat',
        'tanggal',
        'jam',
        'kode_brng',
        'shift',
        'nip',
        'endStop'
    ];

    
}
