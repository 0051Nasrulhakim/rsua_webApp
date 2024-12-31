<?php

namespace App\Models;

use CodeIgniter\Model;

class Penjab extends Model
{
    protected $table            = 'penjab';
    protected $primaryKey       = 'kd_pj';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kd_pj'];

   
}
