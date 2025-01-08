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
        $this->detail_pemberian_obat = new \App\Models\DetailPemberianObat;
        $this->gambar_radiologi = new \App\Models\GambarRadiologi;
    }


    public function index()
    {
        return view('perawat/page/pasien_ranap');
    }

    public function getAllRiwayat()
    {

        // $noRkmMedis = '042837';
        $noRkmMedis = $this->request->getGet('norm');
        // $noRkmMedis = '042831';
        $noRawatRecords = $this->reg_periksa->getNoRawat($noRkmMedis);
        $noRawatList = array_column($noRawatRecords, 'no_rawat');

        $data['pemeriksaan'] = $this->riwayatPeriksa->getPemeriksaanByNoRawat($noRawatList);

        return $this->response->setJSON($data);
    }


    public function getPasien()
    {
        $page = $this->request->getVar('page') ?? 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $dokter = $this->request->getVar('kd_dokter');
        // $dokter = "D0000058";

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

        return $this->response->setJSON([
            'data' => $data,
            'totalRecords' => $totalRecords,
            'perPage' => $perPage,
            'currentPage' => (int)$page,
            'totalPages' => ceil($totalRecords / $perPage)
        ]);
    }

    public function dokterList()
    {
        $data = $this->kamarInap
            ->select("dokter.nm_dokter, dokter.kd_dokter")
            ->join("reg_periksa", "kamar_inap.no_rawat=reg_periksa.no_rawat")
            ->join("dokter", "reg_periksa.kd_dokter=dokter.kd_dokter")
            ->where("dokter.status", "1")
            ->groupBy("dokter.nm_dokter")
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function saveCatatan_perawatan()
    {
        try {
            $data = [
                'tanggal' => date('Ymd'),
                'jam'     => date('His'),
                'no_rawat' => $this->request->getVar('noRawat'),
                'kd_dokter' => 'D0000007',
                'catatan' => $this->request->getPost('catatan'),
            ];

            $this->catatanPerawatan->insert($data);

            return $this->response->setJSON([
                'status_code' => 200,
                'message' => 'Catatan berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status_code' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getCatatan()
    {
        $noRawat = $this->request->getGet('noRawat');
        // $noRawat = '2024/12/07/000013';
        $catatan = $this->catatanPerawatan->where('no_rawat', $noRawat)->orderBy('tanggal', 'DESC')->orderBy('jam', 'DESC')->findAll();

        return $this->response->setJSON($catatan);
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

            if (!$radiologi) {
                $radiologi = '-';
            }

            $data = [
                'status_code'   => 200,
                'message'       => $message,
                'ttv'           => $ttv,
                'radiologi'     => $radiologi
            ];
        } else {
            $data = [
                'status_code' => 404,
                'message' => 'Data tidak ditemukan.',
            ];
        }

        return $this->response->setJSON($data);
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
                        detail_pemberian_obat.no_faktur 
                    ")
            ->join('databarang', 'databarang.kode_brng = detail_pemberian_obat.kode_brng')
            ->where('detail_pemberian_obat.no_rawat', $noRawat)
            ->orderBy('detail_pemberian_obat.tgl_perawatan', 'DESC')
            ->orderBy('detail_pemberian_obat.jam', 'DESC')
            ->findAll();

        return $this->response->setJSON($data);
    }

    public function getGambarRadiologi()
    {
        $noRawat = $this->request->getGet('norawat');
        // $noRawat = "2024/12/04/000058";

        $data = $this->gambar_radiologi
            ->join('hasil_radiologi', 'gambar_radiologi.no_rawat = hasil_radiologi.no_rawat AND gambar_radiologi.tgl_periksa = hasil_radiologi.tgl_periksa AND gambar_radiologi.jam = hasil_radiologi.jam')
            // ->join('hasil_radiologi', 'gambar_radiologi.no_rawat = hasil_radiologi.no_rawat')
            ->where('gambar_radiologi.no_rawat', $noRawat)
            ->orderBy('gambar_radiologi.tgl_periksa', 'DESC')
            ->orderBy('gambar_radiologi.jam', 'DESC')
            // ->first();
            ->findAll();

        return $this->response->setJSON($data);
    }
}
