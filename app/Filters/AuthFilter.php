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

        // user is logged in
        if (!$session->has('logged_in')) {
            return redirect()->to(
                base_url('login')
            );
        }

        $method = $request->getMethod();

        // if ($session->get('role') !== 'petugas') {
        //     return redirect()->to('/unauthorized')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        // }

        // Check CSRF Token
        // $security = Services::security();
        // if (!$security->check()) {
        //     return redirect()->to('/login')->with('error', 'CSRF validation failed.');
        // }

        // Rate Limiting
        $ip = $request->getIPAddress();
        $throttle = Services::throttler();
        if ($throttle->check($ip, 60, MINUTE) === false) {
            return Services::response()
                ->setStatusCode(429)
                ->setBody('Too many requests. Please wait a while.');
        }

        // Hijacking Protection
        if ($session->get('user_agent') !== $request->getUserAgent() ||
            $session->get('ip_address') !== $request->getIPAddress()) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'Session hijacking attempt detected.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing needed
    }
}
