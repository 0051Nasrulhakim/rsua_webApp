<?php

namespace App\Models;

use CodeIgniter\Model;

class RegPeriksa extends Model
{
    protected $table            = 'reg_periksa';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['no_rawat', 'no_rkm_medis', 'kd_poli', 'kd_pj', 'status_lanjut', 'kd_dokter', 'status_bayar'];

    public function getNoRawat($no_rkm_medis)
    {
        return $this->select('no_rawat')
            ->where('stts !=', 'Batal')
            ->where('no_rkm_medis', $no_rkm_medis)
            ->findAll();
    }
}
