<?php

namespace App\Models;

use CodeIgniter\Model;

class Kabupaten extends Model
{
    protected $table            = 'kabupaten';
    protected $primaryKey       = 'kd_kab';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kd_kab', 'nm_kab'];

}
