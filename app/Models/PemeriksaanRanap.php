<?php

namespace App\Models;

use CodeIgniter\Model;

class PemeriksaanRanap extends Model
{
    protected $table            = 'pemeriksaan_ranap';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

    
}
