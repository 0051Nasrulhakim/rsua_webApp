<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class HandOver extends BaseController
{
    public function __construct()
    {
        $this->pasien = new \App\Models\Pasien();
        $this->kamarInap = new \App\Models\KamarInap();
        $this->pemeriksaan_ranap = new \App\Models\PemeriksaanRanap();
        $this->pemeriksaan_ralan = new \App\Models\PemeriksaanRalan;
        $this->reg_periksa = new \App\Models\RegPeriksa();
        $this->radiologi = new \App\Models\HasilRadiologi();
        $this->catatanPerawatan = new \App\Models\CatatanPerawatan();
        $this->riwayatPeriksa = new \App\Models\RiwayatPeriksa;
        $this->detail_pemberian_obat = new \App\Models\DetailPemberianObat;
        $this->gambar_radiologi = new \App\Models\GambarRadiologi;
        $this->periksa_lab = new \App\Models\PeriksaLab;
        $this->template = new \App\Models\TemplateLaboraturium;
        $this->detail_periksa_lab = new \App\Models\DetailPeriksaLab;
        $this->shift_perawatan = new \App\Models\AroShiftCatatanPerawatan;
        $this->TrackerSql = new \App\Models\TrackerSql;
        $this->session = \Config\Services::session();
        helper(['tracker']);
    }

    public function index()
    {
        return view('perawat/page/handOver');
    }

    public function handOverPerTanggal()
    {
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 200;
        $offset = ($page - 1) * $perPage;
        $dokter = $this->request->getGet('kd_dokter');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $builder = $this->kamarInap
            ->join("dpjp_ranap", "kamar_inap.no_rawat=dpjp_ranap.no_rawat", 'LEFT')
            ->join("reg_periksa", "kamar_inap.no_rawat=reg_periksa.no_rawat")
            ->join("dokter", "reg_periksa.kd_dokter=dokter.kd_dokter")
            ->where("kamar_inap.stts_pulang", "-");

        if (!empty($dokter)) {
            $builder->where("dpjp_ranap.kd_dokter", $dokter);
        }

        $totalRecords = $builder->countAllResults(false);

        $data = $this->kamarInap
            ->select("
                kamar_inap.no_rawat,
                reg_periksa.no_rkm_medis,
                pasien.nm_pasien,
                CONCAT(pasien.alamat,', ',kelurahan.nm_kel,', ',kecamatan.nm_kec,', ',kabupaten.nm_kab) AS alamat,
                reg_periksa.p_jawab, reg_periksa.hubunganpj,
                penjab.png_jawab,
                CONCAT(kamar_inap.kd_kamar,' ',bangsal.nm_bangsal) AS kamar,
                kamar_inap.trf_kamar,
                kamar_inap.diagnosa_awal,
                kamar_inap.diagnosa_akhir,
                kamar_inap.tgl_masuk,
                kamar_inap.jam_masuk,
                IF(kamar_inap.tgl_keluar='0000-00-00','',kamar_inap.tgl_keluar) AS tgl_keluar,
                IF(kamar_inap.jam_keluar='00:00:00','',kamar_inap.jam_keluar) AS jam_keluar,
                kamar_inap.ttl_biaya,
                kamar_inap.stts_pulang,
                kamar_inap.lama,
                dokter.nm_dokter,
                kamar_inap.kd_kamar,
                reg_periksa.kd_pj,
                CONCAT(reg_periksa.umurdaftar,' ',reg_periksa.sttsumur) AS umur, reg_periksa.status_bayar,
                pasien.agama,
                aro_catatan_naik_titip_kelas.tanda,
                dokter_dpjp.nm_dokter AS dokter_dpjp,
                dokter_dpjp.kd_dokter AS kode_dokter_dpjp,
                penjab.png_jawab,
                IF(kamar_inap.tgl_keluar != '0000-00-00', 'Pulang', DATEDIFF(CURDATE(), kamar_inap.tgl_masuk)) AS lama_inap,
                kamar.kelas,
                GROUP_CONCAT(DISTINCT IFNULL(diagnosa_pasien.kd_penyakit, '-') SEPARATOR ', ') AS kode_dx,
                GROUP_CONCAT(DISTINCT IFNULL(penyakit.nm_penyakit, '-') SEPARATOR ', ') AS nama_penyakit,
                
                CONCAT(
                    'Pagi: ', IFNULL(
                        (SELECT GROUP_CONCAT(CONCAT(
                            catatan_keperawatan_ranap.jam, ' ',
                            catatan_keperawatan_ranap.uraian, ' ', '\n\n\n-------------------------- \n',
                            petugas.nama, '\n', catatan_keperawatan_ranap.tanggal, aro_shift_catatan_perawatan.jam
                        ) ORDER BY catatan_keperawatan_ranap.jam DESC)
                        FROM catatan_keperawatan_ranap
                        JOIN aro_shift_catatan_perawatan
                            ON catatan_keperawatan_ranap.no_rawat = aro_shift_catatan_perawatan.no_rawat
                            AND catatan_keperawatan_ranap.tanggal = aro_shift_catatan_perawatan.tanggal
                            AND catatan_keperawatan_ranap.jam = aro_shift_catatan_perawatan.jam
                        JOIN petugas  -- Join dengan tabel petugas
                            ON catatan_keperawatan_ranap.nip = petugas.nip  -- Asumsikan tabel petugas punya kolom nip
                        WHERE catatan_keperawatan_ranap.no_rawat = kamar_inap.no_rawat
                        AND aro_shift_catatan_perawatan.no_rawat = kamar_inap.no_rawat
                        AND catatan_keperawatan_ranap.tanggal = '$tanggal'
                        AND aro_shift_catatan_perawatan.tanggal = '$tanggal'
                        AND aro_shift_catatan_perawatan.shift = 'pagi'
                    ), ''),

                    ', Siang: ', IFNULL(
                        (SELECT GROUP_CONCAT(CONCAT(
                            catatan_keperawatan_ranap.jam, ' ',
                            catatan_keperawatan_ranap.uraian, ' ',
                            catatan_keperawatan_ranap.uraian, ' ', '\n\n\n-------------------------- \n',
                            petugas.nama, '\n', catatan_keperawatan_ranap.tanggal, aro_shift_catatan_perawatan.jam
                        ) ORDER BY catatan_keperawatan_ranap.jam DESC)
                        FROM catatan_keperawatan_ranap
                        JOIN aro_shift_catatan_perawatan
                            ON catatan_keperawatan_ranap.no_rawat = aro_shift_catatan_perawatan.no_rawat
                            AND catatan_keperawatan_ranap.tanggal = aro_shift_catatan_perawatan.tanggal
                            AND catatan_keperawatan_ranap.jam = aro_shift_catatan_perawatan.jam
                        JOIN petugas
                            ON catatan_keperawatan_ranap.nip = petugas.nip
                        WHERE catatan_keperawatan_ranap.no_rawat = kamar_inap.no_rawat
                        AND aro_shift_catatan_perawatan.no_rawat = kamar_inap.no_rawat
                        AND catatan_keperawatan_ranap.tanggal = '$tanggal'
                        AND aro_shift_catatan_perawatan.tanggal = '$tanggal'
                        AND aro_shift_catatan_perawatan.shift = 'siang'
                    ), ''),

                    ', Malam: ', IFNULL(
                        (SELECT GROUP_CONCAT(CONCAT(
                            catatan_keperawatan_ranap.jam, ' ',
                            catatan_keperawatan_ranap.uraian, ' ',
                            catatan_keperawatan_ranap.uraian, ' ', '\n\n\n-------------------------- \n',
                            petugas.nama, '\n', catatan_keperawatan_ranap.tanggal, aro_shift_catatan_perawatan.jam
                        ) ORDER BY catatan_keperawatan_ranap.jam DESC)
                        FROM catatan_keperawatan_ranap
                        JOIN aro_shift_catatan_perawatan
                            ON catatan_keperawatan_ranap.no_rawat = aro_shift_catatan_perawatan.no_rawat
                            AND catatan_keperawatan_ranap.tanggal = aro_shift_catatan_perawatan.tanggal
                            AND catatan_keperawatan_ranap.jam = aro_shift_catatan_perawatan.jam
                        JOIN petugas
                            ON catatan_keperawatan_ranap.nip = petugas.nip
                        WHERE catatan_keperawatan_ranap.no_rawat = kamar_inap.no_rawat
                        AND aro_shift_catatan_perawatan.no_rawat = kamar_inap.no_rawat
                        AND catatan_keperawatan_ranap.tanggal = '$tanggal'
                        AND aro_shift_catatan_perawatan.tanggal = '$tanggal'
                        AND aro_shift_catatan_perawatan.shift = 'malam'
                    ), '')
                ) AS catatan
            ")
            ->join("pasien", "reg_periksa.no_rkm_medis=pasien.no_rkm_medis")
            ->join("kamar", "kamar_inap.kd_kamar=kamar.kd_kamar")
            ->join("bangsal", "kamar.kd_bangsal=bangsal.kd_bangsal")
            ->join("kelurahan", "pasien.kd_kel=kelurahan.kd_kel")
            ->join("kecamatan", "pasien.kd_kec=kecamatan.kd_kec")
            ->join("kabupaten", "pasien.kd_kab=kabupaten.kd_kab")
            ->join("diagnosa_pasien", "kamar_inap.no_rawat=diagnosa_pasien.no_rawat", 'LEFT')
            ->join("penyakit", "diagnosa_pasien.kd_penyakit=penyakit.kd_penyakit", 'LEFT')
            ->join("penjab", "reg_periksa.kd_pj=penjab.kd_pj")
            ->join("aro_catatan_naik_titip_kelas", "reg_periksa.no_rawat=aro_catatan_naik_titip_kelas.no_rawat", 'LEFT')
            ->join("dokter AS dokter_dpjp", "dpjp_ranap.kd_dokter=dokter_dpjp.kd_dokter", 'LEFT')
            ->where("kamar_inap.stts_pulang", "-")
            ->groupBy("kamar_inap.no_rawat");
        if (!empty($dokter)) {
            $data = $data->where("dpjp_ranap.kd_dokter", $dokter);
        }
        $data = $data->orderBy('kamar_inap.no_rawat', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        function prosesCatatan($catatan) {
            if (empty($catatan)) {
                return [];
            }

            preg_match_all('/(\d{2}:\d{2}:\d{2})\s*(.*?)\s*(?=(\d{2}:\d{2}:\d{2}|$))/s', $catatan, $hasil);

            return array_map(function ($jam, $uraian) {
                return [
                    'jam' => $jam,
                    'catatan' => trim($uraian)
                ];
            }, $hasil[1], $hasil[2]);
        }

        foreach ($data as &$item) {
            preg_match('/Pagi: (.*?)(?=, Siang:|$)/s', $item['catatan'], $catatanPagi);
            preg_match('/Siang: (.*?)(?=, Malam:|$)/s', $item['catatan'], $catatanSiang);
            preg_match('/Malam: (.*?)$/s', $item['catatan'], $catatanMalam);

            $item['catatan'] = [
                'pagi' => prosesCatatan($catatanPagi[1] ?? ''),
                'siang' => prosesCatatan($catatanSiang[1] ?? ''),
                'malam' => prosesCatatan($catatanMalam[1] ?? ''),
            ];
        }
    

        return $this->response->setJSON([
            'data' => $data,
            'totalRecords' => $totalRecords,
            'perPage' => $perPage,
            'currentPage' => (int)$page,
            'totalPages' => ceil($totalRecords / $perPage)
        ]);
    }

    public function detailCatatan()
    {
        $old_no_rawat = $this->request->getPost('noRawat');
        $old_tanggal = $this->request->getPost('tanggal');
        $jam = $this->request->getPost('jam');

        $data  = $this->catatanPerawatan
            ->where('no_rawat', $old_no_rawat)
            ->where('tanggal', $old_tanggal)
            ->where('jam', $jam)
            ->first();

        if (empty($data)) {

            $res = [
                'status_code' => 200,
                'message' => 'catatan tidak ditemukan'
            ];

            return $this->response->setJSON($res);
        }

        $res = [
            'status_code' => 200,
            'data' => $data
        ];

        return $this->response->setJSON($res);
    }
}
