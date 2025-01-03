<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPeriksa extends Model
{
    protected $returnType = 'array';

    // Metode untuk mendapatkan data pemeriksaan berdasarkan daftar no_rawat
    public function getPemeriksaanByNoRawat(array $noRawatList)
    {
        // Mengambil data dari tabel pemeriksaan_ralan
        $ralanData = $this->db->table('pemeriksaan_ralan')
            ->whereIn('no_rawat', $noRawatList)
            ->get()
            ->getResultArray();

        // Mengambil data dari tabel pemeriksaan_ranap
        $ranapData = $this->db->table('pemeriksaan_ranap')
            ->whereIn('no_rawat', $noRawatList)
            ->get()
            ->getResultArray();

        // Menggabungkan data dari kedua tabel
        return array_merge($ralanData, $ranapData);
    }
}
