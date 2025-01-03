<?php

namespace App\Models;

use CodeIgniter\Model;

class CatatanPerawatan extends Model
{
    protected $table            = 'catatan_perawatan';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'jam',
        'no_rawat',
        'kd_dokter',
        'catatan'
    ];

}
