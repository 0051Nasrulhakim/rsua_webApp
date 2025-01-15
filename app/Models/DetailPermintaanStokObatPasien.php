<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPermintaanStokObatPasien extends Model
{
    protected $table            = 'detail_permintaan_stok_obat_pasien';
    protected $primaryKey       = 'no_permintaan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

}
