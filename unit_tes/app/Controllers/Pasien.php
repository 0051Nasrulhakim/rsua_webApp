<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pasien extends BaseController
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
    }
    public function index()
    {
        return view('perawat/page/pasien_ranap');
    }

    public function unitTes()
    {
        $noRkmMedis = '042505'; // Ganti dengan nilai yang sesuai
        $noRawatRecords = $this->reg_periksa->getNoRawat($noRkmMedis);
        $noRawatList = array_column($noRawatRecords, 'no_rawat');

        $data['pemeriksaan'] = $this->riwayatPeriksa->getPemeriksaanByNoRawat($noRawatList);


        dd($data);
    }

    public function getPasien()
    {

        $page = $this->request->getVar('page') ?? 1; 
        $perPage = 20;  
        $offset = ($page - 1) * $perPage;

        $totalRecords = $this->kamarInap
        ->join("reg_periksa", "kamar_inap.no_rawat=reg_periksa.no_rawat")
        ->countAllResults(false);

        $data = $this->kamarInap
            ->select("
                    kamar_inap.no_rawat,
                    reg_periksa.no_rkm_medis,
                    pasien.nm_pasien,
                    CONCAT(pasien.alamat,', ',kelurahan.nm_kel,', ',kecamatan.nm_kec,', ',kabupaten.nm_kab) AS alamat,
                    reg_periksa.p_jawab,reg_periksa.hubunganpj,
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
                    CONCAT(reg_periksa.umurdaftar,' ',reg_periksa.sttsumur)AS umur,reg_periksa.status_bayar,
                    pasien.agama, 
                    aro_catatan_naik_titip_kelas.tanda
                ")
            // ->join("reg_per  iksa", "kamar_inap.no_rawat=reg_periksa.no_rawat")
            ->join("pasien", "reg_periksa.no_rkm_medis=pasien.no_rkm_medis")
            ->join("kamar", "kamar_inap.kd_kamar=kamar.kd_kamar")
            ->join("bangsal", "kamar.kd_bangsal=bangsal.kd_bangsal")
            ->join("kelurahan", "pasien.kd_kel=kelurahan.kd_kel")
            ->join("kecamatan", "pasien.kd_kec=kecamatan.kd_kec")
            ->join("kabupaten", "pasien.kd_kab=kabupaten.kd_kab")
            ->join("dokter", "reg_periksa.kd_dokter=dokter.kd_dokter")
            ->join("penjab", "reg_periksa.kd_pj=penjab.kd_pj")
            ->join("aro_catatan_naik_titip_kelas", "reg_periksa.no_rawat=aro_catatan_naik_titip_kelas.no_rawat", 'LEFT')
            ->ORDERBY('kamar_inap.no_rawat', 'DESC')
            ->limit($perPage, $offset)
            ->findAll();

        // return $this->response->setJSON($data);
        return $this->response->setJSON([
            'data' => $data,
            'totalRecords' => $totalRecords,
            'perPage' => $perPage,
            'currentPage' => (int)$page,
            'totalPages' => ceil($totalRecords / $perPage)
        ]);
    }


    public function riwayatRanap($noRawat)
    {
        $ttv = $this->pemeriksaan_ranap
            ->select("
                pemeriksaan_ranap.keluhan, 
                pemeriksaan_ranap.pemeriksaan, 
                pemeriksaan_ranap.penilaian, 
                pemeriksaan_ranap.rtl, 
                pemeriksaan_ranap.instruksi, 
                pemeriksaan_ranap.evaluasi,
                TRIM(CONCAT(
                    IF(pemeriksaan_ranap.nadi IS NOT NULL AND pemeriksaan_ranap.nadi != '', CONCAT('Nadi: ', pemeriksaan_ranap.nadi, ', '), ''),
                    IF(pemeriksaan_ranap.tensi IS NOT NULL AND pemeriksaan_ranap.tensi != '', CONCAT('Tensi: ', pemeriksaan_ranap.tensi, ', '), ''),
                    IF(pemeriksaan_ranap.respirasi IS NOT NULL AND pemeriksaan_ranap.respirasi != '', CONCAT('Respirasi: ', pemeriksaan_ranap.respirasi, ', '), ''),
                    IF(pemeriksaan_ranap.suhu_tubuh IS NOT NULL AND pemeriksaan_ranap.suhu_tubuh != '', CONCAT('Suhu : ', pemeriksaan_ranap.suhu_tubuh, ', '), ''),
                    IF(pemeriksaan_ranap.spo2 IS NOT NULL AND pemeriksaan_ranap.spo2 != '', CONCAT('Spo2: ', pemeriksaan_ranap.spo2, ', '), ''),
                    IF(pemeriksaan_ranap.gcs IS NOT NULL AND pemeriksaan_ranap.gcs != '', CONCAT('GCS: ', pemeriksaan_ranap.gcs, ', '), ''),
                    IF(pemeriksaan_ranap.kesadaran IS NOT NULL AND pemeriksaan_ranap.kesadaran != '', CONCAT('Kesadaran: ', pemeriksaan_ranap.kesadaran, ', '), '')
                )) AS data_pemeriksaan
            ")
            ->where('no_rawat', $noRawat)
            ->orderBy('tgl_perawatan', 'DESC')
            ->orderBy('jam_rawat', 'DESC')
            ->limit(1)
            // ->findAll();
            ->first();
        // dd($ttv);
        return $ttv;
    }

    public function riWayatRalan($noRawat)
    {
        // $noRawat = $this->request->getGet('noRawat');
        $ttv = $this->pemeriksaan_ralan
            ->select("
            pemeriksaan_ralan.keluhan, 
            pemeriksaan_ralan.pemeriksaan, 
            pemeriksaan_ralan.penilaian, 
            pemeriksaan_ralan.rtl, 
            pemeriksaan_ralan.instruksi, 
            pemeriksaan_ralan.evaluasi,
                TRIM(CONCAT(
                    IF(pemeriksaan_ralan.nadi IS NOT NULL AND pemeriksaan_ralan.nadi != '', CONCAT('Nadi: ', pemeriksaan_ralan.nadi, ', '), ''),
                    IF(pemeriksaan_ralan.tensi IS NOT NULL AND pemeriksaan_ralan.tensi != '', CONCAT('Tensi: ', pemeriksaan_ralan.tensi, ', '), ''),
                    IF(pemeriksaan_ralan.respirasi IS NOT NULL AND pemeriksaan_ralan.respirasi != '', CONCAT('Respirasi: ', pemeriksaan_ralan.respirasi, ', '), ''),
                    IF(pemeriksaan_ralan.suhu_tubuh IS NOT NULL AND pemeriksaan_ralan.suhu_tubuh != '', CONCAT('Suhu: ', pemeriksaan_ralan.suhu_tubuh, ', '), ''),
                    IF(pemeriksaan_ralan.spo2 IS NOT NULL AND pemeriksaan_ralan.spo2 != '', CONCAT('Spo2: ', pemeriksaan_ralan.spo2, ', '), ''),
                    IF(pemeriksaan_ralan.gcs IS NOT NULL AND pemeriksaan_ralan.gcs != '', CONCAT('GCS: ', pemeriksaan_ralan.gcs, ', '), ''),
                    IF(pemeriksaan_ralan.kesadaran IS NOT NULL AND pemeriksaan_ralan.kesadaran != '', CONCAT('Kesadaran : ', pemeriksaan_ralan.kesadaran, ''), '')
                )) AS data_pemeriksaan
            ")
            ->where('no_rawat', $noRawat)
            ->orderBy('tgl_perawatan', 'DESC')
            ->orderBy('jam_rawat', 'DESC')
            ->limit(1)
            ->first();

        return $ttv;
    }

    public function getRadiologi($noRawat)
    {
        $radiologi = $this->radiologi
                    ->where('no_rawat', $noRawat)
                    ->orderBy('tgl_periksa', 'DESC')
                    ->orderBy('jam', 'DESC')
                    ->limit(1)
                    ->first();

        return $radiologi;
    }

    public function saveCatatan_perawatan()
    {
        $data = [
            'tanggal' => date('Ymd'),
            'jam'     => date('His'),
            'no_rawat' => $this->request->getVar('noRawat'),
            'kd_dokter' => 'D0000007',
            'catatan' => $this->request->getPost('catatan'),
        ];

        $this->catatanPerawatan->insert($data);
    }


    public function riwayatSOAPIE()
    {
        $noRekam_medis = $this->request->getGet('norm');
        $data = [];

        $status = $this->reg_periksa
            ->select('reg_periksa.no_reg, reg_periksa.no_rawat, reg_periksa.tgl_registrasi, reg_periksa.status_lanjut')
            ->where('reg_periksa.stts !=', 'Batal')
            ->where('reg_periksa.no_rkm_medis', $noRekam_medis)
            ->orderBy('reg_periksa.tgl_registrasi', 'DESC')
            ->limit(1)
            // ->findAll();
            ->first();


        if ($status) {
            if ($status['status_lanjut'] == 'Ralan') {
                $ttv = $this->riWayatRalan($status['no_rawat']);
                $message = 'Data Ralan ditemukan';
            } else {
                $ttv = $this->riwayatRanap($status['no_rawat']);
                $message = 'Data Ranap ditemukan';
            }

            $radiologi = $this->getRadiologi($status['no_rawat']);

            if(!$radiologi) {
                $radiologi = '-';
            }

            // Data respons sukses
            $data = [
                'status_code'   => 200,
                'message'       => $message,
                'ttv'           => $ttv,
                'radiologi'     => $radiologi
            ];
        } else {
            // Data tidak ditemukan
            $data = [
                'status_code' => 404,
                'message' => 'Data tidak ditemukan.',
            ];
        }

        // dd($data);

        return $this->response->setJSON($data);
    }
    
}
