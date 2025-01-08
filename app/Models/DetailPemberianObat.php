<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPemberianObat extends Model
{
    protected $table            = 'detail_pemberian_obat';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];
    
}
