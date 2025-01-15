<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPemberianObat extends Model
{
    protected $table            = 'detail_pemberian_obat';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tgl_perawatan',
        'jam',
        'no_rawat',
        'kode_brng',
        'h_beli',
        'biaya_obat',
        'jml',
        'embalse',
        'tuslah',
        'total',
        'status',
        'kd_bangsal',
        'no_batch',
        'no_faktur'
    ];
    
}
