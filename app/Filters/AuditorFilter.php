<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuditorFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login/auditor')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (session()->get('role') !== 'auditor') {
            // Jika admin mencoba akses URL auditor
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("403 Forbidden: Anda tidak memiliki akses ke halaman ini.");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
