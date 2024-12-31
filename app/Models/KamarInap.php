<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarInap extends Model
{
    protected $table            = 'kamar_inap';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];
}
