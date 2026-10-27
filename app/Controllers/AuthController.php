<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;

class AuthController extends Controller {
    
    // 1. Menampilkan halaman Login
    public function login() {
        require_once BASE_PATH . '/resources/views/auth/login.php';
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
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Sementara arahkan ke halaman utama atau landing page setelah sukses
            header('Location: /');
            exit;
        } else {
            // Anda bisa menambahkan penanganan error flash message di sini
            echo "<script>alert('Email atau password salah!'); window.location='/login';</script>";
        }
    }

    // 3. Menampilkan halaman Register
    public function register() {
        require_once BASE_PATH . '/resources/views/auth/register.php';
    }

    // 4. Memproses data Register (POST)
    public function processRegister() {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();

        // Mencegah pendaftaran dengan email yang sama
        if ($userModel->findByEmail($email)) {
            echo "<script>alert('Email sudah terdaftar!'); window.location='/register';</script>";
            return;
        }

        // Mengenkripsi kata sandi sebelum masuk ke database
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        // Menyimpan user baru (role default 'buyer')
        $userModel->create($name, $email, $passwordHash, 'buyer');

        echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='/login';</script>";
    }

    // 5. Menghapus sesi pengguna
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login');
        exit;
    }
}