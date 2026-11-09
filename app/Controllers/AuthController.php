<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;

class AuthController extends Controller {
    // 1. Menampilkan halaman Login
    public function login() {
        // Pengguna yang sudah login tidak perlu melihat form login
        if (isset($_SESSION['user_id'])) {
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
    public function processLogin() {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Verifikasi keberadaan user dan kecocokan password hash
        if ($user && password_verify($password, $user['password'])) {
            // Daftarkan sesi pengguna
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['public_id']  = $user['public_id'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['email']   = $user['email'];

            // Mengambil bio dari database (jika belum diisi, pakai bio default)
            $_SESSION['bio']     = $user['bio'] ?? 'Hanya game developer pemula :v';

            // Nama toko dipakai navbar dan halaman dashboard penjual
            if ($user['role'] === 'seller') {
                $profile = (new \IndoFX\Models\SellerProfile())->find((int) $user['id']);
                $_SESSION['store_name'] = $profile['store_name'] ?? $user['name'];
            }
            
            // Fitur "Ingat Saya"
            if (isset($_POST['remember_me'])) {
                // Buat token acak sepanjang 64 karakter
                $token = bin2hex(random_bytes(32)); 
                // Simpan token ke Supabase
                $userModel->updateRememberToken($user['id'], $token);    
                // Tanam cookie di browser pengguna selama 30 hari
                setcookie('remember_token', $token, time() + (86400 * 30), "/", "", false, true);
            }

            $this->redirect('/');
        }

        // Login gagal: simpan pesan lalu kembali ke form
        $_SESSION['login_error'] = 'Email atau password salah!';
        $_SESSION['login_old']   = ['email' => $email];

        $this->redirect('/login');
    }

    // 3. Menampilkan halaman Register
    public function register() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->view('auth/register', [
            'error' => $_SESSION['register_error'] ?? null,
            'old'   => $_SESSION['register_old'] ?? [],
        ]);

        unset($_SESSION['register_error'], $_SESSION['register_old']);
    }

    // 4. Memproses data Register (POST)
    public function processRegister() {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();

        // Mencegah pendaftaran dengan email yang sama
        if ($userModel->findByEmail($email)) {
            $_SESSION['register_error'] = 'Email sudah terdaftar!';
            $_SESSION['register_old']   = ['name' => $name, 'email' => $email];

            $this->redirect('/register');
        }

        // Mengenkripsi kata sandi sebelum masuk ke database
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Menyimpan user baru (role default 'buyer')
        $userModel->create($name, $email, $passwordHash, 'buyer');

        $_SESSION['login_success'] = 'Registrasi berhasil! Silakan masuk.';
        $_SESSION['login_old']     = ['email' => $email];

        $this->redirect('/login');
    }

    // 5. Menghapus sesi pengguna
    public function logout() {
        // Hapus token dari database jika user memiliki cookie
        if (isset($_COOKIE['remember_token']) && isset($_SESSION['user_id'])) {
            $userModel = new User();
            $userModel->updateRememberToken($_SESSION['user_id'], null);
        }

        // Hapus cookie dari browser (atur waktu ke masa lalu)
        setcookie('remember_token', '', time() - 3600, '/');

        session_unset();
        session_destroy();

        $this->redirect('/login');
    }
}