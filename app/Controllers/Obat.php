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
        $this->session = \Config\Services::session();
        $this->TrackerSql = new \App\Models\TrackerSql;
        helper(['tracker']);
    }

    public function getRiwayatObat()
    {
        $noRawat = $this->request->getGet('norawat');
        // $noRawat = "2024/12/06/000123";
        $tangalSekarang = date('Y-m-d');

        $data = $this->detail_pemberian_obat
            ->select("
                detail_pemberian_obat.tgl_perawatan,
                GROUP_CONCAT(detail_pemberian_obat.jam ORDER BY detail_pemberian_obat.jam DESC SEPARATOR ', ') AS jam,
                detail_pemberian_obat.no_rawat,
                detail_pemberian_obat.kode_brng,
                databarang.nama_brng,
                detail_pemberian_obat.embalase,
                detail_pemberian_obat.tuslah,
                SUM(detail_pemberian_obat.jml) AS jml,
                detail_pemberian_obat.biaya_obat,
                detail_pemberian_obat.total,
                detail_pemberian_obat.h_beli,
                detail_pemberian_obat.kd_bangsal,
                detail_pemberian_obat.no_batch,
                detail_pemberian_obat.no_faktur
            ")
            ->join('databarang', 'databarang.kode_brng = detail_pemberian_obat.kode_brng')
            ->where('detail_pemberian_obat.no_rawat', $noRawat)
            ->groupBy('databarang.nama_brng, detail_pemberian_obat.tgl_perawatan')
            ->orderBy('detail_pemberian_obat.tgl_perawatan', 'DESC')
            ->orderBy('detail_pemberian_obat.jam', 'DESC')

            ->findAll();

        return $this->response->setJSON($data);
    }


    public function getStokObatPasien()
    {
        $noRawat = $this->request->getGet('norawat');
        // $noRawat = '2024/12/06/000124';

        $tanggal = $this->request->getGet('tanggal');
        // $tanggal = '2025-01-14';
        // dd($tanggal, $noRawat);

        $data = $this->stok_obat_pasien
            ->select('
                    stok_obat_pasien.*, 
                    databarang.*,
                    jenis.nama as jenis,
                    (stok_obat_pasien.jumlah - IFNULL(subquery.total_pemberian, 0)) AS sisa_stok,
                    IFNULL(subquery.last_insert_time, "-") AS last_insert_time,
                    subquery.jam_pemberian,
                    GROUP_CONCAT(DISTINCT aro_riwayat_pemberian_obat.label_jam_pemberian ORDER BY aro_riwayat_pemberian_obat.label_jam_pemberian SEPARATOR ",") AS label_jam_diberikan,
                    (
                        SELECT kamar.kelas 
                        FROM kamar 
                        INNER JOIN kamar_inap ON kamar.kd_kamar = kamar_inap.kd_kamar 
                        WHERE kamar_inap.no_rawat = stok_obat_pasien.no_rawat 
                        AND kamar_inap.stts_pulang = "-"
                    ) AS kelas_kamar
                ')
            ->join('databarang', 'databarang.kode_brng = stok_obat_pasien.kode_brng')
            ->join('jenis', 'databarang.kdjns = jenis.kdjns')
            ->join(
                '(SELECT kode_brng, no_rawat, tgl_perawatan, 
                        SUM(jml) AS total_pemberian, 
                        MAX(CONCAT(tgl_perawatan, " ", jam)) AS last_insert_time, 
                        GROUP_CONCAT(DISTINCT jam ORDER BY jam SEPARATOR ",") AS jam_pemberian 
                        FROM detail_pemberian_obat 
                        GROUP BY kode_brng, no_rawat, tgl_perawatan) AS subquery',
                'subquery.kode_brng = stok_obat_pasien.kode_brng AND 
                    subquery.no_rawat = stok_obat_pasien.no_rawat AND 
                    subquery.tgl_perawatan = stok_obat_pasien.tanggal',
                'LEFT'
            )
            ->join(
                'aro_riwayat_pemberian_obat',
                'aro_riwayat_pemberian_obat.kode_barang = stok_obat_pasien.kode_brng AND
                    aro_riwayat_pemberian_obat.no_rawat = stok_obat_pasien.no_rawat AND 
                    aro_riwayat_pemberian_obat.tanggal = stok_obat_pasien.tanggal',
                'LEFT'
            )
            ->where('stok_obat_pasien.no_rawat', $noRawat)
            ->where('stok_obat_pasien.tanggal', $tanggal)
            ->groupBy('stok_obat_pasien.kode_brng')
            ->findAll();
        foreach ($data as &$item) {
            $item['jam_pemberian'] = isset($item['jam_pemberian']) ? array_map('trim', explode(',', $item['jam_pemberian'])) : [];
            $item['label_jam_diberikan'] = isset($item['label_jam_diberikan']) ? array_map('trim', explode(',', $item['label_jam_diberikan'])) : [];

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
                $item['jadwal_pemberian'] = array_map(function ($jam) use ($item) {
                    return [
                        'jadwal' => $jam,
                        'status' => in_array($jam, $item['label_jam_diberikan']) ? 'diberikan' : 'belum diberikan'
                    ];
                }, $item['jadwal_pemberian']);
            } else {
                $item['jadwal_pemberian'] = [];
            }

            $kelasMapping = [
                'Kelas 1' => 'kelas1',
                'Kelas 2' => 'kelas2',
                'Kelas 3' => 'kelas3',
                'Utama/BPJS' => 'utama',
                'VIP' => 'vip',
                'VVIP' => 'vvip',
                'Beli Luar' => 'beliluar',
                'Karyawan' => 'karyawan',
            ];
            $item['harga_obat'] = $item[$kelasMapping[$item['kelas_kamar']] ?? 'jualbebas'];

            $columnsToUnset = [
                'ralan',
                'kelas1',
                'kelas2',
                'kelas3',
                'utama',
                'vip',
                'vvip',
                'beliluar',
                'jualbebas',
                'karyawan',
                'stokminimal',
                'kdjns',
                'isi',
                'kapasitas'
            ];
            foreach ($columnsToUnset as $col) {
                unset($item[$col]);
            }
        }

        return $this->response->setJSON($data);
    }


    public function getCpo()
    {
        $noRawat = $this->request->getGet('norawat');
        // $noRawat = "2024/12/06/000124";
        
        $tanggalTerakhir = $this->detail_pemberian_obat
            ->select("detail_pemberian_obat.tgl_perawatan")
            ->where('detail_pemberian_obat.no_rawat', $noRawat)
            ->groupBy('detail_pemberian_obat.tgl_perawatan')
            ->orderBy('detail_pemberian_obat.tgl_perawatan', 'DESC')
            ->limit(3)
            ->findAll();

        if (!$tanggalTerakhir) {
            $data = [
                'status_code' => 400,
                'message' => 'Data Catatan Pemberian Obat Tidak Ditemukan'
            ];
            return $this->response->setJSON($data);
        }

        $tanggalArray = array_column($tanggalTerakhir, 'tgl_perawatan');

        $dataObat = $this->detail_pemberian_obat
            ->select("
            detail_pemberian_obat.kode_brng,
            databarang.nama_brng,
            detail_pemberian_obat.tgl_perawatan,
            GROUP_CONCAT(DISTINCT aro_riwayat_pemberian_obat.label_jam_pemberian ORDER BY aro_riwayat_pemberian_obat.label_jam_pemberian SEPARATOR ',') AS label_jam_diberikan,
            GROUP_CONCAT(DISTINCT aro_riwayat_pemberian_obat.jam ORDER BY aro_riwayat_pemberian_obat.jam SEPARATOR ',') AS jam_pemberian
        ")
            ->join('databarang', 'databarang.kode_brng = detail_pemberian_obat.kode_brng')
            ->join(
                'aro_riwayat_pemberian_obat',
                'aro_riwayat_pemberian_obat.kode_barang = detail_pemberian_obat.kode_brng AND
            aro_riwayat_pemberian_obat.no_rawat = detail_pemberian_obat.no_rawat AND 
            aro_riwayat_pemberian_obat.tanggal = detail_pemberian_obat.tgl_perawatan',
                'LEFT'
            )
            ->where('detail_pemberian_obat.no_rawat', $noRawat)
            ->whereIn('detail_pemberian_obat.tgl_perawatan', $tanggalArray)
            ->groupBy('databarang.nama_brng, detail_pemberian_obat.tgl_perawatan')
            ->orderBy('detail_pemberian_obat.tgl_perawatan', 'DESC')
            ->orderBy('databarang.nama_brng', 'ASC')
            ->findAll();
        // dd($dataObat);
        $tanggalGrouped = [];
        foreach ($dataObat as $item) {
            $tgl = $item['tgl_perawatan'];

            $labelJamDiberikanArray = explode(', ', $item['label_jam_diberikan']);
            $jamPemberianArray = explode(', ', $item['jam_pemberian']);

            $labelJamMap = array_combine($labelJamDiberikanArray, $jamPemberianArray);

            $jamByCategory = [
                'pagi' => [],
                'siang' => [],
                'sore' => [],
                'malam' => []
            ];

            foreach ($labelJamMap as $jam => $waktu) {
                // dd($jam);
                if (strtotime($jam) >= strtotime("07:00:00") && strtotime($jam) < strtotime("12:00:00")) {
                    $jamByCategory['pagi'][] = ['jam_pemberian' => $jam, 'waktu' => $waktu];
                } elseif (strtotime($jam) >= strtotime("12:00:00") && strtotime($jam) < strtotime("16:00:00")) {
                    $jamByCategory['siang'][] = ['jam_pemberian' => $jam, 'waktu' => $waktu];
                } elseif (strtotime($jam) >= strtotime("16:00:00") && strtotime($jam) < strtotime("20:00:00")) {
                    $jamByCategory['sore'][] = ['jam_pemberian' => $jam, 'waktu' => $waktu];
                } elseif (strtotime($jam) >= strtotime("20:00:00") || strtotime($jam) < strtotime("07:00:00")) {
                    $jamByCategory['malam'][] = ['jam_pemberian' => $jam, 'waktu' => $waktu];
                } else {
                    $jamByCategory['pagi'] = [];
                    $jamByCategory['siang'] = [];
                    $jamByCategory['sore'] = [];
                    $jamByCategory['malam'] = [];
                }
            }

            $tanggalGrouped[$tgl][] = [
                'obat' => $item['nama_brng'],
                'kd_obat' => $item['kode_brng'],
                'label_jam_diberikan' => $jamByCategory
            ];
        }

        $tanggalArray = array_keys($tanggalGrouped);

        $listObat = $this->detail_pemberian_obat
            ->select('databarang.nama_brng, detail_pemberian_obat.kode_brng')
            ->where('detail_pemberian_obat.no_rawat', $noRawat)
            ->join('databarang', 'databarang.kode_brng = detail_pemberian_obat.kode_brng')
            ->orderBy('detail_pemberian_obat.tgl_perawatan', 'DESC')
            ->groupBy('databarang.nama_brng')
            ->whereIn('detail_pemberian_obat.tgl_perawatan', $tanggalArray)
            ->findAll();

        $data = [
            'status_code' => 200,
            'list_obat' => $dataObat,
            'list_tanggal' => $tanggalGrouped,
            'daftar_nama_obat' => $listObat
        ];

        return $this->response->setJSON($data);
    }


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
        // $data = json_decode($this->request->getBody(), true);

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

                $this->detail_pemberian_obat->insert([
                    'tgl_perawatan' => $item['tanggal'],
                    'jam' => $item['jam'],
                    'no_rawat' => $item['no_rawat'],
                    'kode_brng' => $item['kode_brng'],
                    'jml' => $item['jumlah'],
                    'kd_bangsal' => $item['kd_bangsal'],
                    'no_batch' => $item['no_batch'],
                    'no_faktur' => $item['no_faktur'],
                    'status' => "Ranap",
                    'h_beli' => $item['h_beli'],
                    'biaya_obat' => $item['biaya_obat'],
                    'total' => $item['total'],
                ]);

                $lastQuery = getLastQuery();
                $logsql = $this->session->get('ip_address') . '| ' . $lastQuery;
                $this->TrackerSql->insertTracker($logsql, $this->session->get('nip'));

                $this->aro_riwayat_pemberian_obat->insert([
                    'tanggal' => $item['tanggal'],
                    'jam' => $item['jam'],
                    'no_rawat' => $item['no_rawat'],
                    'kode_barang' => $item['kode_brng'],
                    'label_jam_pemberian' => $item['periode'],
                ]);
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
