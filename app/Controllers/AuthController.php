<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;

class AuthController extends Controller
{
    // 1. Menampilkan halaman Login
    public function login()
    {
        // Pengguna yang sudah login dialihkan sesuai perannya
        if (isset($_SESSION['user_id'])) {
            if (($_SESSION['role'] ?? '') === 'admin') {
                $this->redirect('/admin/dashboard');
            }
            $this->redirect('/');
        }

        $this->view('auth/login', [
            'error'   => $_SESSION['login_error'] ?? null,
            'success' => $_SESSION['login_success'] ?? null,
            'old'     => $_SESSION['login_old'] ?? [],
        ]);

        unset($_SESSION['login_error'], $_SESSION['login_success'], $_SESSION['login_old']);
    }

    // 2. Memproses data Login (POST)
    public function processLogin()
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Verifikasi keberadaan user dan kecocokan password hash
        if ($user && password_verify($password, $user['password_hash'])) {
            // Daftarkan sesi pengguna
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['name'];

            // Mengambil bio dari database
            $_SESSION['bio']     = $user['bio'] ?? 'Halo saya adalah pengguna baru';

            // PENGALIHAN ADMIN: Seragam ke Dashboard Utama Admin
            if ($user['role'] === 'admin') {
                $this->redirect('/admin/dashboard');
            }

            $this->redirect('/');
        }

        // Login gagal: simpan pesan lalu kembali ke form
        $_SESSION['login_error'] = 'Email atau password salah!';
        $_SESSION['login_old']   = ['email' => $email];

        $this->redirect('/login');
    }

    // 3. Menampilkan halaman Register
    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            if (($_SESSION['role'] ?? '') === 'admin') {
                $this->redirect('/admin/dashboard');
            }
            $this->redirect('/');
        }

        $this->view('auth/register', [
            'error' => $_SESSION['register_error'] ?? null,
            'old'   => $_SESSION['register_old'] ?? [],
        ]);

        unset($_SESSION['register_error'], $_SESSION['register_old']);
    }

    // 4. Memproses data Register (POST)
    public function processRegister()
    {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $_SESSION['register_error'] = 'Email sudah terdaftar!';
            $_SESSION['register_old']   = ['name' => $name, 'email' => $email];

            $this->redirect('/register');
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $userModel->create($name, $email, $passwordHash, 'buyer');

        $_SESSION['login_success'] = 'Registrasi berhasil! Silakan masuk.';
        $_SESSION['login_old']     = ['email' => $email];

        $this->redirect('/login');
    }

    // 5. Menghapus sesi pengguna
    public function logout()
    {
        session_unset();
        session_destroy();

        $this->redirect('/login');
    }
}
