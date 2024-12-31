<?php

namespace App\Models;

use CodeIgniter\Model;

class AroCatatanNaikTitipKelas extends Model
{
    protected $table            = 'aro_catatan_naikt_itip_kelas';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_rawat','tanda'
    ];

}
