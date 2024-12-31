<?php

namespace App\Models;

use CodeIgniter\Model;

class Kecamatan extends Model
{
    protected $table            = 'kecamatan';
    protected $primaryKey       = 'kd_kec';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kd_kec', 'nm_kec'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

}
