<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            // Redirect to dashboard or appropriate page for app-pkpt-new-1
            return redirect()->to('/dashboard'); 
        }

        return view('auth/login');
    }

    public function attempt(): RedirectResponse
    {
        $session = session();
        $username = $this->request->getPost('username_ldap');

        if (! $username) {
            $session->setFlashdata('error', 'Username LDAP wajib diisi');
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $user = $userModel->where('username_ldap', $username)->first();

        if (! $user) {
            $session->setFlashdata('error', 'Pengguna tidak ditemukan');
            return redirect()->back()->withInput();
        }

        if (isset($user['is_active']) && (int) $user['is_active'] !== 1) {
            $session->setFlashdata('error', 'Akun tidak aktif');
            return redirect()->back()->withInput();
        }

        // Ambil data detail pegawai dari View (untuk Auto-fill Form)
        $pegawaiModel = new \App\Models\PegawaiViewModel();
        $pegawaiDetail = null;
        if (!empty($user['pegawai_id'])) {
            $pegawaiDetail = $pegawaiModel->getDetail($user['pegawai_id']);
        }

        $session->set([
            'isLoggedIn' => true,
            'user'       => [
                'id'            => $user['id'] ?? null,
                'name'          => $user['username_ldap'],
                'username_ldap' => $user['username_ldap'],
                'role_id'       => $user['role_id'] ?? null,
                'pegawai_id'    => $user['pegawai_id'] ?? null,
                // Simpan detail pegawai lengkap untuk akses data unit kerja & nama asli
                'pegawai_detail'=> $pegawaiDetail 
            ],
        ]);

        return redirect()->to('/dashboard'); // Adjusted redirect for app-pkpt-new-1
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
