<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPeriksaLab extends Model
{
    protected $table            = 'detail_periksa_lab';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];
}
