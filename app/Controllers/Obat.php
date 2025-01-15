<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Obat extends BaseController
{
    public function __construct()
    {
        $this->detail_pemberian_obat = new \App\Models\DetailPemberianObat;
        $this->permintaan_stok_obat_pasien = new \App\Models\PermintanObatPasien;
        $this->detail_permintaan_stok_obat_pasien = new \App\Models\DetailPermintaanStokObatPasien;
        $this->stok_obat_pasien = new \App\Models\StokObatPasien;
    }

    public function getRiwayatObat()
    {
        $noRawat = $this->request->getGet('norawat');
        // $noRawat = "2024/12/04/000019";

        $data = $this->detail_pemberian_obat
            ->select("
                        detail_pemberian_obat.tgl_perawatan,
                        detail_pemberian_obat.jam,
                        detail_pemberian_obat.no_rawat,
                        detail_pemberian_obat.kode_brng,
                        databarang.nama_brng,
                        detail_pemberian_obat.embalase,
                        detail_pemberian_obat.tuslah,
                        detail_pemberian_obat.jml,
                        detail_pemberian_obat.biaya_obat,
                        detail_pemberian_obat.total,
                        detail_pemberian_obat.h_beli,
                        detail_pemberian_obat.kd_bangsal,
                        detail_pemberian_obat.no_batch,
                        detail_pemberian_obat.no_faktur 
                    ")
            ->join('databarang', 'databarang.kode_brng = detail_pemberian_obat.kode_brng')
            ->where('detail_pemberian_obat.no_rawat', $noRawat)
            ->orderBy('detail_pemberian_obat.tgl_perawatan', 'DESC')
            ->orderBy('detail_pemberian_obat.jam', 'DESC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getStokObatPasien()
    {
        $noRawat = $this->request->getGet('norawat');
        $noRawat = "2024/12/06/000069";
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $data = $this->stok_obat_pasien
            ->select('
                    stok_obat_pasien.*, 
                    databarang.nama_brng,
                    (stok_obat_pasien.jumlah - IFNULL(SUM(detail_pemberian_obat.jml), 0)) AS sisa_stok
                ')

            ->join('databarang', 'databarang.kode_brng = stok_obat_pasien.kode_brng')
            ->join(
                'detail_pemberian_obat',
                '
                        detail_pemberian_obat.kode_brng = stok_obat_pasien.kode_brng AND 
                        detail_pemberian_obat.no_rawat = stok_obat_pasien.no_rawat AND 
                        detail_pemberian_obat.tgl_perawatan = stok_obat_pasien.tanggal',
                'LEFT'
            )

            ->where('stok_obat_pasien.no_rawat', $noRawat)
            ->groupBy('stok_obat_pasien.kode_brng')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function simpanPemberianObat()
    {
        $data = json_decode($this->request->getBody(), true); 

        if ($data === null) {
            return $this->response->setJSON(['message' => 'Data JSON tidak valid'], 400);
        }
        if (!empty($data)) {
            foreach ($data as $item) {
                $this->detail_pemberian_obat->insert([
                    'tgl_perawatan' => date('Y-m-d'),
                    'jam' => $item['jam'],
                    'no_rawat' => $item['no_rawat'],
                    'kode_brng' => $item['kode_brng'],
                    'jml' => $item['jumlah'],
                    'status' => "Ranap"
                ]);
            }
            return $this->response->setJSON(['message' => 'Data berhasil disimpan']);
        } else {
            return $this->response->setJSON(['message' => 'Tidak ada data untuk disimpan'], 400);
        }
    }
}
