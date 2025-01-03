<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilRadiologi extends Model
{
    protected $table            = 'hasil_radiologi';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

}
