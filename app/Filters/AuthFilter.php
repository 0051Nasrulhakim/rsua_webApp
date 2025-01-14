<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->has('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        $method = $request->getMethod();

        if ($session->get('akses') !== 'petugas') {
            return redirect()->to('login');
        }

        // Rate Limiting
        // $ip = $request->getIPAddress();
        // $throttle = Services::throttler();
        // if ($throttle->check($ip, 400, MINUTE) === false) {
        //     return Services::response()
        //         ->setStatusCode(429)
        //         ->setBody('Terlalu Banyak Melakukan Refresh Tunggu Beberapa Saat');
        // }
        // dd($request);

        // Hijacking Protection
        if ($session->get('user_agent') != $request->getUserAgent() ||
            $session->get('ip_address') != $request->getIPAddress()) {
            $session->destroy();
            return redirect()->to(base_url('login'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    
    }
}
