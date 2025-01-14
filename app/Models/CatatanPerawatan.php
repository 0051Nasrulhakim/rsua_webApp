<?php

namespace App\Models;

use CodeIgniter\Model;

class CatatanPerawatan extends Model
{
    protected $table            = 'catatan_keperawatan_ranap';
    protected $primaryKey       = 'no_rawat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tanggal',
        'jam',
        'no_rawat',
        'uraian',
        'nip'
    ];

    function tes($data)
    {
        $this->insert($data);
    }
}
