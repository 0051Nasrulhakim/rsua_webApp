<?php

namespace App\Models;

use CodeIgniter\Model;

class GambarRadiologi extends Model
{
    protected $table            = 'gambar_radiologi';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

}
