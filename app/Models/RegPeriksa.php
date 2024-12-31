<?php

namespace App\Models;

use CodeIgniter\Model;

class RegPeriksa extends Model
{
    protected $table            = 'regperiksa';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['no_rawat', 'no_rkm_medis', 'kd_poli', 'kd_pj', 'status_lanjut', 'kd_dokter', 'status_bayar'];

}