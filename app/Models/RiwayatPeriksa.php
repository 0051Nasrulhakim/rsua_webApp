<?php

namespace App\Models;

use CodeIgniter\Model;

class RiwayatPeriksa extends Model
{
    protected $returnType = 'array';

    public function getPemeriksaanByNoRawat(array $noRawatList)
    {

        $ralanData = $this->db->table('pemeriksaan_ralan')
            ->whereIn('no_rawat', $noRawatList)
            ->select('
                pemeriksaan_ralan.no_rawat, 
                pemeriksaan_ralan.tgl_perawatan, 
                pemeriksaan_ralan.jam_rawat, 
                pemeriksaan_ralan.keluhan, 
                pemeriksaan_ralan.pemeriksaan, 
                pemeriksaan_ralan.penilaian, 
                pemeriksaan_ralan.rtl, 
                pemeriksaan_ralan.instruksi, 
                pemeriksaan_ralan.evaluasi, 
                pemeriksaan_ralan.nadi, 
                pemeriksaan_ralan.tensi, 
                pemeriksaan_ralan.respirasi, 
                pemeriksaan_ralan.suhu_tubuh, 
                pemeriksaan_ralan.spo2, 
                pemeriksaan_ralan.gcs, 
                pemeriksaan_ralan.kesadaran, 
                pemeriksaan_ralan.nip, 
                petugas.nama, 
                "ralan" as source')
            ->join('petugas', 'pemeriksaan_ralan.nip = petugas.nip')
            ->get()
            ->getResultArray();

        $ranapData = $this->db->table('pemeriksaan_ranap')
            ->whereIn('no_rawat', $noRawatList)
            ->select('
                pemeriksaan_ranap.no_rawat, 
                pemeriksaan_ranap.tgl_perawatan, 
                pemeriksaan_ranap.jam_rawat, 
                pemeriksaan_ranap.keluhan, 
                pemeriksaan_ranap.pemeriksaan, 
                pemeriksaan_ranap.penilaian, 
                pemeriksaan_ranap.rtl, 
                pemeriksaan_ranap.instruksi, 
                pemeriksaan_ranap.evaluasi, 
                pemeriksaan_ranap.nadi, 
                pemeriksaan_ranap.tensi, 
                pemeriksaan_ranap.respirasi, 
                pemeriksaan_ranap.suhu_tubuh, 
                pemeriksaan_ranap.spo2, 
                pemeriksaan_ranap.gcs, 
                pemeriksaan_ranap.kesadaran,
                pemeriksaan_ranap.nip, 
                petugas.nama, 
                "ranap" as source')
            ->join('petugas', 'pemeriksaan_ranap.nip = petugas.nip')
            ->get()
            ->getResultArray();

        $radiologiData = [];
        foreach ($noRawatList as $noRawat) {
            $hasilRadiologi = $this->db->table('hasil_radiologi')
                ->where('no_rawat', $noRawat)
                ->select('no_rawat, tgl_periksa as tgl_perawatan, jam as jam_rawat, hasil, "radiologi" as source')
                ->get()
                ->getRowArray();

            if ($hasilRadiologi) {
                $radiologiData[$noRawat] = $hasilRadiologi;
            } else {

                $radiologiData[$noRawat] = [
                    'no_rawat' => $noRawat,
                    'tgl_perawatan' => '-',
                    'jam_rawat' => '-',
                    'hasil' => '-',
                    'source' => 'radiologi'
                ];
            }
        }


        $mergedData = [];

        foreach (array_merge($ralanData, $ranapData) as $data) {
            $noRawat = $data['no_rawat'];

            if (isset($data['nadi'], $data['tensi'], $data['respirasi'], $data['suhu_tubuh'], $data['spo2'], $data['gcs'], $data['kesadaran'])) {
                $data['ttv'] = trim(join(", ", array_filter([
                    $data['nadi'] ? 'Nadi: ' . $data['nadi'] : null,
                    $data['tensi'] ? 'Tensi: ' . $data['tensi'] : null,
                    $data['respirasi'] ? 'Respirasi: ' . $data['respirasi'] : null,
                    $data['suhu_tubuh'] ? 'Suhu: ' . $data['suhu_tubuh'] : null,
                    $data['spo2'] ? 'Spo2: ' . $data['spo2'] : null,
                    $data['gcs'] ? 'GCS: ' . $data['gcs'] : null,
                    $data['kesadaran'] ? 'Kesadaran: ' . $data['kesadaran'] : null
                ])));
                unset($data['nadi'], $data['tensi'], $data['respirasi'], $data['suhu_tubuh'], $data['spo2'], $data['gcs'], $data['kesadaran']);
            } else {
                $data['ttv'] = '-';
            }

            if (!isset($mergedData[$noRawat])) {
                $mergedData[$noRawat] = [];
            }

            $mergedData[$noRawat][] = $data;
        }

        foreach ($mergedData as $noRawat => &$data) {

            $data = array_slice($data, 0, 10);
            usort($data, function ($a, $b) {
                $dateTimeA = strtotime($a['tgl_perawatan'] . ' ' . ($a['jam_rawat'] ?? '00:00:00'));
                $dateTimeB = strtotime($b['tgl_perawatan'] . ' ' . ($b['jam_rawat'] ?? '00:00:00'));
                return $dateTimeB <=> $dateTimeA;
            });
        }

        foreach ($radiologiData as $noRawat => $radiologi) {
            if (isset($mergedData[$noRawat])) {
                $mergedData[$noRawat][] = $radiologi;
            } else {
                $mergedData[$noRawat] = [$radiologi];
            }
        }
        return $mergedData;
    }
}
