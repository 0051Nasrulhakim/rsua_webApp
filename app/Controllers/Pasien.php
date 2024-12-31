<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Pasien extends BaseController
{
    public function __construct()
    {
        $this->pasien = new \App\Models\Pasien();
        $this->kamarInap = new \App\Models\KamarInap;
    }
    public function index()
    {
        return view('perawat/page/pasien_ranap');
    }

    public function getPasien()
    {
        $page = 1; // Halaman pertama (bisa disesuaikan)
        $perPage = 20; // Jumlah data per halaman
        $offset = ($page - 1) * $perPage;

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
            ->join("reg_periksa", "kamar_inap.no_rawat=reg_periksa.no_rawat")
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

        return $this->response->setJSON($data);
    }
}