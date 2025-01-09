<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriksaLab extends Model
{
    protected $table            = 'periksa_lab';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

    public function getAllLab()
    {
        
    }

}
