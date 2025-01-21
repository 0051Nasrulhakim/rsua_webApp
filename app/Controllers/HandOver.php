<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class HandOver extends BaseController
{
    public function index()
    {
        return view('perawat/page/handOver');
    }
}
