<?php

namespace App\Models;

use CodeIgniter\Model;

class Kelurahan extends Model
{
    protected $table            = 'kelurahan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kd_kel', 'nm_kel'];
}
