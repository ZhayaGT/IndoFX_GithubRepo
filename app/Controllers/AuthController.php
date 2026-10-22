<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;

class AuthController extends Controller {
    
    // 1. Menampilkan halaman Login
    public function login() {
        echo "<h1>Login IndoFX</h1>";
        echo "<form method='POST' action='/login'>
                <input type='email' name='email' placeholder='Email' required><br><br>
                <input type='password' name='password' placeholder='Password' required><br><br>
                <button type='submit'>Masuk</button>
              </form>";
        echo "<p>Belum punya akun? <a href='/register'>Daftar di sini</a></p>";
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

            echo "<h3>Login Berhasil!</h3>";
            echo "Selamat datang, " . htmlspecialchars($user['name']) . " (Role: " . htmlspecialchars($user['role']) . ")";
            echo "<br><br><a href='/logout'>Logout</a>";
        } else {
            echo "<h3>Login Gagal</h3><p>Email atau password salah!</p>";
            echo "<a href='/login'>Kembali</a>";
        }
    }

    // 3. Menampilkan halaman Register
    public function register() {
        echo "<h1>Register IndoFX</h1>";
        echo "<form method='POST' action='/register'>
                <input type='text' name='name' placeholder='Nama Lengkap' required><br><br>
                <input type='email' name='email' placeholder='Email' required><br><br>
                <input type='password' name='password' placeholder='Password' required><br><br>
                <button type='submit'>Daftar</button>
              </form>";
    }

    // 4. Memproses data Register (POST)
    public function processRegister() {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();

        // Mencegah pendaftaran dengan email yang sama
        if ($userModel->findByEmail($email)) {
            echo "<h3>Registrasi Gagal</h3><p>Email sudah terdaftar!</p>";
            echo "<a href='/register'>Kembali</a>";
            return;
        }

        // Mengenkripsi kata sandi sebelum masuk ke database
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        // Menyimpan user baru (role default 'buyer')
        $userModel->create($name, $email, $passwordHash, 'buyer');

        echo "<h3>Registrasi Berhasil!</h3><p>Akun Anda telah dibuat.</p>";
        echo "<a href='/login'>Silakan Login</a>";
    }

    // 5. Menghapus sesi pengguna
    public function logout() {
        session_unset();
        session_destroy();
        
        echo "<h3>Anda telah logout.</h3>";
        echo "<a href='/login'>Kembali ke Login</a>";
    }
}