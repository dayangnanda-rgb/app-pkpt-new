<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            return redirect()->to("/dashboard/$role");
        }

        return view('auth/login');
    }

    public function loginAdmin()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard/admin');
        }
        return view('auth/login_admin', ['role' => 'admin']);
    }

    public function loginAuditor()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard/auditor');
        }
        return view('auth/login_auditor', ['role' => 'auditor']);
    }

    public function attemptUser()
    {
        // Standard User / SSO Login Logic
        // We'll use handleLogin but allow any role to enter via this main portal
        // and redirect based on their stored role.
        return $this->handleLoginGeneric();
    }

    public function attemptAdmin()
    {
        return $this->handleLogin('admin');
    }

    public function attemptAuditor()
    {
        return $this->handleLogin('auditor');
    }

    private function handleLoginGeneric(): RedirectResponse
    {
        $session = session();
        $username = $this->request->getPost('username_ldap');
        // Password optional for main portal (SSO style)
        $password = $this->request->getPost('password');

        if (!$username) {
            $session->setFlashdata('error', 'Username wajib diisi');
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $user = $userModel->where('username_ldap', $username)->first();

        $invalidError = 'Username tidak ditemukan atau Password salah';

        if (!$user) {
            $session->setFlashdata('error', $invalidError);
            return redirect()->back()->withInput();
        }

        // Logic check: If password is provided, verify it. 
        // If not provided (coming from main SSO form), allow entry for simulation/SSO.
        if ($password && !password_verify($password, $user['password'])) {
            $session->setFlashdata('error', $invalidError);
            return redirect()->back()->withInput();
        }

        if (isset($user['is_active']) && (int) $user['is_active'] !== 1) {
            $session->setFlashdata('error', 'Akun tidak aktif');
            return redirect()->back()->withInput();
        }

        $role_id = (int)$user['role_id'];
        if ($role_id === 1) {
            // Hanya akun generic 'admin' yang mendapatkan hak akses penuh Admin
            // Akun personil (dengan nama) meskipun role_id 1, dianggap 'user'
            $roleName = ($user['username_ldap'] === 'admin') ? 'admin' : 'user';
        } elseif ($role_id === 2) {
            $roleName = 'auditor';
        } else {
            $roleName = 'user';
        }

        return $this->finalizeLogin($user, $roleName);
    }

    private function handleLogin(string $expectedRole): RedirectResponse
    {
        $session = session();
        $username = $this->request->getPost('username_ldap');
        $password = $this->request->getPost('password');

        if (!$username || !$password) {
            $session->setFlashdata('error', 'Username dan Password wajib diisi');
            return redirect()->back()->withInput();
        }

        $userModel = new UserModel();
        $user = $userModel->where('username_ldap', $username)->first();

        $invalidError = 'Username atau Password salah';

        if (!$user) {
            $session->setFlashdata('error', $invalidError);
            return redirect()->back()->withInput();
        }

        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', $invalidError);
            return redirect()->back()->withInput();
        }

        if (isset($user['is_active']) && (int) $user['is_active'] !== 1) {
            $session->setFlashdata('error', 'Akun tidak aktif');
            return redirect()->back()->withInput();
        }

        $role_id = (int)$user['role_id'];
        if ($role_id === 1) {
            $roleName = ($user['username_ldap'] === 'admin') ? 'admin' : 'user';
        } elseif ($role_id === 2) {
            $roleName = 'auditor';
        } else {
            $roleName = 'user';
        }

        if ($roleName !== $expectedRole) {
            $session->setFlashdata('error', "Akun Anda terdaftar sebagai " . ucfirst($roleName) . ". Silakan login melalui portal yang sesuai.");
            return redirect()->back()->withInput();
        }

        return $this->finalizeLogin($user, $roleName);
    }

    private function finalizeLogin($user, string $roleName): RedirectResponse
    {
        $session = session();
        $pegawaiModel = new \App\Models\PegawaiViewModel();
        $pegawaiDetail = null;
        if (!empty($user['pegawai_id'])) {
            $pegawaiDetail = $pegawaiModel->getDetail($user['pegawai_id']);
        }

        $session->regenerate();
        $session->set([
            'logged_in'     => true,
            'isLoggedIn'    => true,
            'user_id'       => $user['id'],
            'role'          => $roleName,
            'user'          => [
                'id'            => $user['id'],
                'name'          => $user['username_ldap'],
                'username_ldap' => $user['username_ldap'],
                'role_id'       => $user['role_id'],
                'pegawai_id'    => $user['pegawai_id'],
                'pegawai_detail'=> $pegawaiDetail 
            ],
        ]);

        return redirect()->to("/dashboard/$roleName");
    }

    public function logout(): RedirectResponse
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
