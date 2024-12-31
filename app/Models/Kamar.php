<?php

namespace App\Models;

use CodeIgniter\Model;

class Kamar extends Model
{
    protected $table            = 'kamar';
    protected $primaryKey       = 'kd_kamar';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kd_kamar', 'kd_bangsal', 'trf_kamar', 'status', 'kelas', 'statusdata'];

}
