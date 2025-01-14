<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{


	public function __construct()
	{
		$this->user = new \App\Models\User();
		$this->petugas = new \App\Models\Petugas();
		$this->session = \Config\Services::session();
		$this->dokter = new \App\Models\Dokter();
	}

	public function checklogin()
	{

		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		if ($username == '' || $password == '') {
			$res = [
				'status_code' 	=> 300,
				'message' 		=> "Username atau Password tidak boleh kosong",
			];

			return $this->response->setJSON($res);
		}

		$response = $this->getDataLogin($username, $password);
		return $response;
	}


	public function getDataLogin($username, $password)
	{
		// $username = '2410010098';
		// $password = '2410010098';
		$cekIsnotNull = $this->user->getData($username, $password);

		if ($cekIsnotNull > 0) {

			$getDataPetugas = $this->petugas
				->join('jabatan', 'petugas.kd_jbtn = jabatan.kd_jbtn')
				->where('petugas.nip', $username)
				->where('petugas.status', '1')->first();

			if ($getDataPetugas == null) {
				$data = [
					'status_code' 	=> 300,
					'message' => 'Status Petugas Tidak Aktif',
				];
				return $this->response->setJSON($data);
			}

			$hakAkses = 'petugas';
			$cekDokter = $this->dokter->where('kd_dokter', $username)->first();

			if ($cekDokter) {
				$hakAkses = "dokter";
			}

			$sessionData = [
				'nip' => $getDataPetugas['nip'],
				'nama' => $getDataPetugas['nama'],
				'jabatan' => $getDataPetugas['nm_jbtn'],
				'kd_jabatan' => $getDataPetugas['kd_jbtn'],
				'akses' => $hakAkses,
				'logged_in' => TRUE,
				'user_agent' => $this->request->getUserAgent(),
				'ip_address' => $this->request->getIPAddress(),
			];
			// dd($sessionData);
			$this->session->set($sessionData);

			$data = [
				'status_code' => 200,
				'message' => 'Berhasil Melakukan Login dengan user ' . $getDataPetugas['nama'],
			];

			return $this->response->setJSON($data);
		} else {

			$data = [
				'status_code' => 300,
				'message' => 'USER TIDAK DITEMUKAN',
			];

			return $this->response->setJSON($data);
		}
	}
}
