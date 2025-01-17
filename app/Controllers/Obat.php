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
        $this->aro_riwayat_pemberian_obat = new \App\Models\AroRiwayatPemberianObat;
    }

    public function getRiwayatObat()
    {
        $noRawat = $this->request->getGet('norawat');

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
                        detail_pemberian_obat.no_faktur,
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
        $noRawat = "2024/12/07/000013"; 
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $data = $this->stok_obat_pasien
            ->select('
            stok_obat_pasien.*, 
            databarang.nama_brng,
            jenis.nama as jenis,
            (stok_obat_pasien.jumlah - IFNULL(SUM(detail_pemberian_obat.jml), 0)) AS sisa_stok,
            IFNULL(MAX(CONCAT(detail_pemberian_obat.tgl_perawatan, " ", detail_pemberian_obat.jam)), "-") AS last_insert_time,
            GROUP_CONCAT(DISTINCT detail_pemberian_obat.jam ORDER BY detail_pemberian_obat.jam SEPARATOR ",") AS jam_pemberian,
            GROUP_CONCAT(DISTINCT aro_riwayat_pemberian_obat.label_jam_pemberian ORDER BY aro_riwayat_pemberian_obat.label_jam_pemberian SEPARATOR ",") AS label_jam_diberikan
        ')
            ->join('databarang', 'databarang.kode_brng = stok_obat_pasien.kode_brng')
            ->join('jenis', 'databarang.kdjns = jenis.kdjns')
            ->join(
                'detail_pemberian_obat',
                '
                detail_pemberian_obat.kode_brng = stok_obat_pasien.kode_brng AND 
                detail_pemberian_obat.no_rawat = stok_obat_pasien.no_rawat AND 
                detail_pemberian_obat.tgl_perawatan = stok_obat_pasien.tanggal',
                'LEFT'
            )
            ->join(
                'aro_riwayat_pemberian_obat',
                '
                aro_riwayat_pemberian_obat.kode_barang = stok_obat_pasien.kode_brng AND
                aro_riwayat_pemberian_obat.no_rawat = stok_obat_pasien.no_rawat AND 
                aro_riwayat_pemberian_obat.tanggal = stok_obat_pasien.tanggal
            ',
                'LEFT'
            )
            ->where('stok_obat_pasien.no_rawat', $noRawat)
            ->where('stok_obat_pasien.tanggal', $tanggal)
            ->groupBy('stok_obat_pasien.kode_brng')
            ->findAll();

        foreach ($data as &$item) {
            
            if (isset($item['jam_pemberian'])) {
                $item['jam_pemberian'] = explode(',', $item['jam_pemberian']);
            } else {
                $item['jam_pemberian'] = [];
            }

            if (isset($item['label_jam_diberikan'])) {
                $item['label_jam_diberikan'] = explode(',', $item['label_jam_diberikan']);
            } else {
                $item['label_jam_diberikan'] = [];
            }

            $jamTrue = [];
            for ($i = 0; $i <= 23; $i++) {
                $jamKey = 'jam' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (isset($item[$jamKey]) && $item[$jamKey] === 'true') {
                    $jamTrue[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                }
                unset($item[$jamKey]);
            }

            if (empty($item['jadwal_pemberian']) && !empty($jamTrue)) {
                $item['jadwal_pemberian'] = $jamTrue;
            }

            if (isset($item['jadwal_pemberian'])) {
                $jadwalPemberian = [];
                foreach ($item['jadwal_pemberian'] as $jam) {

                    $status = in_array($jam, $item['label_jam_diberikan']) ? 'diberikan' : 'belum diberikan';

                    $jadwalPemberian[] = [
                        'jadwal' => $jam,
                        'status' => $status
                    ];
                }
                $item['jadwal_pemberian'] = $jadwalPemberian;
            } else {
                $item['jadwal_pemberian'] = [];
            }
            
        }

        return $this->response->setJSON($data);
    }



    // public function getStokObatPasien()
    // {
    //     $noRawat = $this->request->getGet('norawat');
    //     $noRawat = "2024/12/07/000013";
    //     $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
    //     $data = $this->stok_obat_pasien
    //         ->select('
    //                 stok_obat_pasien.*, 
    //                 databarang.nama_brng,
    //                 (stok_obat_pasien.jumlah - IFNULL(SUM(detail_pemberian_obat.jml), 0)) AS sisa_stok,
    //                 IFNULL(MAX(CONCAT(detail_pemberian_obat.tgl_perawatan, " ", detail_pemberian_obat.jam)), "-") AS last_insert_time

    //             ')

    //         ->join('databarang', 'databarang.kode_brng = stok_obat_pasien.kode_brng')
    //         ->join(
    //             'detail_pemberian_obat',
    //             '
    //                     detail_pemberian_obat.kode_brng = stok_obat_pasien.kode_brng AND 
    //                     detail_pemberian_obat.no_rawat = stok_obat_pasien.no_rawat AND 
    //                     detail_pemberian_obat.tgl_perawatan = stok_obat_pasien.tanggal',
    //             'LEFT'
    //         )

    //         ->where('stok_obat_pasien.no_rawat', $noRawat)
    //         ->where('stok_obat_pasien.tanggal', $tanggal)
    //         ->groupBy('stok_obat_pasien.kode_brng')
    //         ->findAll();

    //     return $this->response->setJSON($data);
    // }

    // public function getStokObatPasien()
    // {
    //     $noRawat = $this->request->getGet('norawat');
    //     $noRawat = "2024/12/07/000013";
    //     $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

    //     $data = $this->stok_obat_pasien
    //         ->select('
    //             stok_obat_pasien.*, 
    //             databarang.nama_brng,
    //             (stok_obat_pasien.jumlah - IFNULL(SUM(detail_pemberian_obat.jml), 0)) AS sisa_stok,
    //             GROUP_CONCAT(detail_pemberian_obat.jam) AS jam_diberikan
    //         ')
    //         ->join('databarang', 'databarang.kode_brng = stok_obat_pasien.kode_brng')
    //         ->join(
    //             'detail_pemberian_obat',
    //             '
    //                 detail_pemberian_obat.kode_brng = stok_obat_pasien.kode_brng AND 
    //                 detail_pemberian_obat.no_rawat = stok_obat_pasien.no_rawat AND 
    //                 detail_pemberian_obat.tgl_perawatan = stok_obat_pasien.tanggal',
    //             'LEFT'
    //         )
    //         ->where('stok_obat_pasien.no_rawat', $noRawat)
    //         ->where('stok_obat_pasien.tanggal', $tanggal)
    //         ->groupBy('stok_obat_pasien.kode_brng')
    //         ->findAll();

    //     foreach ($data as &$item) {
    //         $jamDiberikan = $item['jam_diberikan'] ? explode(',', $item['jam_diberikan']) : null;
    //         $item['jam_diberikan'] = $jamDiberikan;
    //     }

    //     return $this->response->setJSON($data);
    // }


    public function getWaktuByJam($jam)
    {
        if ($jam >= 7 && $jam < 12) {
            return ['label' => 'pagi', 'start' => '07:00:00', 'end' => '12:00:00'];
        } elseif ($jam >= 12 && $jam < 16) {
            return ['label' => 'siang', 'start' => '12:00:00', 'end' => '16:00:00'];
        } elseif ($jam >= 16 && $jam < 20) {
            return ['label' => 'sore', 'start' => '16:00:00', 'end' => '20:00:00'];
        } elseif ($jam >= 20 || $jam < 7) {
            return ['label' => 'malam', 'start' => '20:00:00', 'end' => '07:00:00'];
        }
    }

    public function simpanPemberianObat()
    {
        $data = json_decode($this->request->getBody(), true);

        if ($data === null) {
            return $this->response->setJSON(['message' => 'Data JSON tidak valid'], 400);
        }

        if (!empty($data)) {

            $errors = [];

            foreach ($data as $item) {
                $waktuInfo = $this->getWaktuByJam((int)$item['jam']);
                $label = $waktuInfo['label'];
                $start = $waktuInfo['start'];
                $end = $waktuInfo['end'];

                // Cek apakah sudah ada obat yang diberikan dalam rentang waktu yang sama
                // $existing = $this->detail_pemberian_obat
                //     ->select('detail_pemberian_obat.*, databarang.nama_brng')
                //     ->join('databarang', 'databarang.kode_brng = detail_pemberian_obat.kode_brng')
                //     ->where('detail_pemberian_obat.tgl_perawatan', date('Y-m-d'))
                //     ->where('detail_pemberian_obat.no_rawat', $item['no_rawat'])
                //     ->where('detail_pemberian_obat.kode_brng', $item['kode_brng'])
                //     ->where('detail_pemberian_obat.jam >=', $start)
                //     ->where('detail_pemberian_obat.jam <', $end)
                //     ->first();

                // if ($existing) {
                //     $errors[] = "Obat {$existing['nama_brng']} sudah diberikan pada waktu $label";
                // } else {

                $this->detail_pemberian_obat->insert([
                    // 'tgl_perawatan' => date('Y-m-d'),
                    'tgl_perawatan' => $item['tanggal'],
                    'jam' => $item['jam'],
                    'no_rawat' => $item['no_rawat'],
                    'kode_brng' => $item['kode_brng'],
                    'jml' => $item['jumlah'],
                    'status' => "Ranap"
                ]);

                //tracker disini cuy

                $this->aro_riwayat_pemberian_obat->insert([
                    'tanggal' => $item['tanggal'],
                    'jam' => $item['jam'],
                    'no_rawat' => $item['no_rawat'],
                    'kode_barang' => $item['kode_brng'],
                    'label_jam_pemberian' => $item['periode'],
                ]);
                // }
            }

            if (!empty($errors)) {
                $data = [
                    'status_code' => 400,
                    'message' => implode('; ', $errors),
                ];
                return $this->response->setJSON($data);
            } else {
                $data = [
                    'status_code' => 200,
                    'message' => "Data Berhasil Disimpan",
                    'keterangan' => $waktuInfo
                ];
                return $this->response->setJSON($data);
            }
        } else {

            $data = [
                'status_code' => 400,
                'message' => "Tidak Ada Data Yang Disimpan",
            ];
            return $this->response->setJSON($data);
        }
    }
}
