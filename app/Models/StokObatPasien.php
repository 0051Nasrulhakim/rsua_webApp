<?php

namespace App\Models;

use CodeIgniter\Model;

class StokObatPasien extends Model
{
    protected $table            = 'stok_obat_pasien';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];
}
