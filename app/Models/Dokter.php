<?php

namespace App\Models;

use CodeIgniter\Model;

class Dokter extends Model
{
    protected $table            = 'dokter';
    protected $primaryKey       = 'kd_dokter';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'kd_dokter', 'kd_sps', 'nm_dokter', 'jk', 'tmp_lahir', 'tgl_lahir', 'gol_drh', 'agama', 'almt_tgl', 'no_telp', 'stts_nikah', 'alumni', 'no_ijin_praktek', 'kd_dokter', 'status'
    ];
}
