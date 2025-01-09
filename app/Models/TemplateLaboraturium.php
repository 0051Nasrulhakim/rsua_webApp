<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateLaboraturium extends Model
{
    protected $table            = 'template_laboratorium';
    protected $primaryKey       = 'kd_jenis_prw';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [];

}
