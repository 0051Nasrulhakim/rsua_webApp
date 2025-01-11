<?php

namespace App\Models;

use CodeIgniter\Model;

class AroShiftCatatanPerawatan extends Model
{
    protected $table            = 'aro_shift_catatan_perawatan';
    protected $primaryKey       = 'no_rawat';
    protected $returnType       = 'array';
    protected $allowedFields    = ['no_rawat', 'tanggal', 'jam', 'shift', 'nip'];

}
