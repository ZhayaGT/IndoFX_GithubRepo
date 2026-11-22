<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;

class AuthController extends Controller {
    // 1. Menampilkan halaman Login
    public function login() {
        // Pengguna yang sudah login tidak perlu melihat form login
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
    public function processLogin() {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Verifikasi keberadaan user dan kecocokan password hash
        if ($user && $user['is_active'] && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            unset($_SESSION['admin_csrf']);
            // Daftarkan sesi pengguna
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['public_id'] = $user['public_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['store_name'] = $user['store_name'] ?? $user['name'];

            // Mengambil bio dari database
            $_SESSION['bio'] = $user['bio'] ?? 'Halo saya adalah pengguna baru';

            // Fitur "Ingat Saya"
            if (isset($_POST['remember_me'])) {
                // Buat token acak sepanjang 64 karakter
                $token = bin2hex(random_bytes(32)); 
                // Simpan token ke PostgreSQL
                $userModel->updateRememberToken($user['id'], $token);    
                // Tanam cookie di browser pengguna selama 30 hari
                setcookie('remember_token', $token, [
                    'expires' => time() + (86400 * 30), 'path' => '/',
                    'secure' => \IndoFX\Core\Env::get('APP_ENV') === 'production',
                    'httponly' => true, 'samesite' => 'Lax',
                ]);
            }

            $this->redirect(($_SESSION['role'] ?? '') === 'admin' ? '/admin/dashboard' : '/');
        }

        // Login gagal: simpan pesan lalu kembali ke form
        $_SESSION['login_error'] = 'Email atau password salah!';
        $_SESSION['login_old']   = ['email' => $email];

        $this->redirect('/login');
    }

    // 3. Menampilkan halaman Register
    public function register() {
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
    public function processRegister() {
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
    public function logout() {
        // Hapus token dari database jika user masih memiliki sesi aktif
        if (isset($_SESSION['user_id'])) {
            $userModel = new \IndoFX\Models\User();
            // Mengosongkan kolom remember_token dengan mengirimkan nilai null
            $userModel->updateRememberToken($_SESSION['user_id'], null);
        }

        session_unset();
        session_destroy();

        // Hapus cookie remember_token di browser pengguna
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }

        $this->redirect('/login');
    }

    public function sellerRegister() {
        $application = isset($_SESSION['user_id'])
            ? (new \IndoFX\Models\SellerProfile())->find((int) $_SESSION['user_id']) : null;
        $this->view('auth/seller_register', ['sellerApplication' => $application]);
    }

    public function processSellerRegister() {
        if (in_array($_SESSION['role'] ?? '', ['admin', 'seller'], true)) {
            $this->redirect($_SESSION['role'] === 'admin' ? '/admin/dashboard' : '/dashboard');
        }
        $isLoggedIn = isset($_SESSION['user_id']);
        $userModel  = new \IndoFX\Models\User();

        $storeName       = trim($_POST['store_name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $address         = trim($_POST['address'] ?? '');
        $reason          = trim($_POST['reason'] ?? '');
        $assetDesc       = trim($_POST['sample_asset_desc'] ?? '');
        $portfolioUrl    = trim($_POST['portfolio_url'] ?? '');
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $agreeTerms      = isset($_POST['agree_terms']);

        // 1. Validasi Input Dasar
        if (!$agreeTerms) {
            $_SESSION['error_msg'] = 'Harap setujui Syarat dan Ketentuan terlebih dahulu.';
            $this->redirect('/seller/register');
        }

        if ($storeName === '' || $email === '' || $address === '' || $reason === '' || $assetDesc === '') {
            $_SESSION['error_msg'] = 'Semua kolom wajib harus diisi.';
            $this->redirect('/seller/register');
        }

        // 2. Validasi Akun Baru (jika belum login)
        $passwordHash = null;
        if (!$isLoggedIn) {
            if (strlen($password) < 6) {
                $_SESSION['error_msg'] = 'Kata sandi minimal 6 karakter.';
                $this->redirect('/seller/register');
            }
            if ($password !== $passwordConfirm) {
                $_SESSION['error_msg'] = 'Konfirmasi kata sandi tidak cocok.';
                $this->redirect('/seller/register');
            }
            if ($userModel->findByEmail($email)) {
                $_SESSION['error_msg'] = 'Email sudah terdaftar. Silakan login terlebih dahulu lalu daftar jadi penjual.';
                $this->redirect('/seller/register');
            }
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        }

        // 3. Proses Upload File Contoh Aset
        $sampleAssetPath = null;
        if (isset($_FILES['sample_asset']) && $_FILES['sample_asset']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['sample_asset']['name'], PATHINFO_EXTENSION));
            $allowedExt = ['unitypackage', 'zip', 'rar', 'fbx'];

            if (!in_array($ext, $allowedExt)) {
                $_SESSION['error_msg'] = 'Format file harus .unitypackage, .zip, .rar, atau .fbx.';
                $this->redirect('/seller/register');
            }

            $uploadDir = BASE_PATH . '/public/uploads/seller_samples/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = 'sample_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($_FILES['sample_asset']['tmp_name'], $uploadDir . $fileName)) {
                $sampleAssetPath = '/uploads/seller_samples/' . $fileName;
            }
        } else {
            $_SESSION['error_msg'] = 'File contoh aset wajib diunggah.';
            $this->redirect('/seller/register');
        }

        // 4. Simpan ke Database Supabase
        try {
            $user = $userModel->createSellerApplication([
                'store_name'        => $storeName,
                'email'             => $email,
                'password_hash'     => $passwordHash,
                'address'           => $address,
                'reason'            => $reason,
                'portfolio_url'    => $portfolioUrl !== '' ? $portfolioUrl : null,
                'sample_asset_path' => $sampleAssetPath,
                'sample_asset_desc' => $assetDesc
            ], $isLoggedIn ? $_SESSION['user_id'] : null);

            // Jika sebelumnya tamu (belum login), langsung set session login
            if (!$isLoggedIn && $user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['public_id'] = $user['public_id'];
                $_SESSION['name']      = $user['name'];
                $_SESSION['email']     = $user['email'];
                $_SESSION['role']      = $user['role'];
            }

            $_SESSION['success_msg'] = 'Pengajuan menjadi Penjual berhasil dikirim! Menunggu verifikasi Admin IndoFX.';
            $this->redirect('/seller/register');
        } catch (\Exception $e) {
            $_SESSION['error_msg'] = 'Gagal menyimpan pendaftaran: ' . $e->getMessage();
            $this->redirect('/seller/register');
        }
    }
}