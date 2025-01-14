<?php

namespace App\Models;

use CodeIgniter\Model;

class TrackerSql extends Model
{
    protected $table            = 'trackersql';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'tanggal', 'sqle', 'usere'
    ];

    public function insertTracker($logsql, $nip)
    {
        $tracker = [
            'tanggal' => date('Y-m-d H:i:s'),
            'sqle' => (string)$logsql,
            'usere' => $nip
        ];
        
        return $this->insert($tracker);
    }

}
