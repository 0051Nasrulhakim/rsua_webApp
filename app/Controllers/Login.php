<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function __construct()
	{
		$this->session = \Config\Services::session();
	}

    public function index()
    {
        //
        return view("login/login_integrated");
    }

    public function actionLogin()
    {
        echo "halo login";
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url('login'));
    }
}
