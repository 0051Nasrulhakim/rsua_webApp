<?php

namespace App\Models;

use CodeIgniter\Model;

class Bangsal extends Model
{
    protected $table            = 'bangsal';
    protected $primaryKey       = 'kd_bangsal';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kd_bangsal', 'nm_bangsal', 'status'];


}
