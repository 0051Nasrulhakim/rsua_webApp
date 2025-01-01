<?php

namespace App\Models;

use CodeIgniter\Model;

class PemeriksaanRalan extends Model
{
    protected $table            = 'pemeriksaan_ralan';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

}
