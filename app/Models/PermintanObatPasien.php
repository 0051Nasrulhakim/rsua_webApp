<?php

namespace App\Models;

use CodeIgniter\Model;

class PermintanObatPasien extends Model
{
    protected $table            = 'permintaan_stok_obat_pasien';
    protected $primaryKey       = 'no_permintaan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];



}
