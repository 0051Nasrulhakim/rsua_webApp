<?php

namespace App\Models;

use CodeIgniter\Model;

class Pasien extends Model
{
    protected $table            = 'pasien';
    protected $primaryKey       = 'no_rkm_medis';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['no_rkm_medis', 'kd_pj', 'kd_kec', 'kd_kab', 'nm_pasien', 'alamat', 'kd_kel', 'no_ktp', 'no_peserta', 'perusahaan_pasien', 'suku_bangsa', 'bahasa_pasien', 'cacat_fisik', 'kd_prop'];

   public function getPasien()
   {

   }
}
