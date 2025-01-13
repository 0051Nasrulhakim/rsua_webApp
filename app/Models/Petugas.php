<?php

namespace App\Models;

use CodeIgniter\Model;

class Petugas extends Model
{
    protected $table            = 'petugas';
    protected $primaryKey       = 'nip';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

}
