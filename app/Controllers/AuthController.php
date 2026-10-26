<?php

declare(strict_types=1);

namespace IndoFX\Controllers;

use IndoFX\Core\Auth;
use IndoFX\Core\Controller;
use IndoFX\Core\Csrf;
use IndoFX\Core\Session;
use IndoFX\Core\Validator;
use IndoFX\Models\User;

/**
 * Registrasi, login, dan logout pengguna.
 */
class AuthController extends Controller
{
    private const LOGIN_PATH = '/auth/login';
    private const REGISTER_PATH = '/auth/register';

    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->view('auth/login', [
            'errors'  => Session::pullFlash('errors', []),
            'old'     => Session::pullFlash('old', []),
            'success' => Session::pullFlash('success'),
            'csrf'    => Csrf::token(),
        ]);
    }

    public function login(): void
    {
        if (!Csrf::verify($_POST['_token'] ?? null)) {
            $this->redirectWithErrors(self::LOGIN_PATH, ['_token' => 'Sesi formulir kedaluwarsa. Silakan coba lagi.']);
        }

        $input = [
            'email'    => trim((string) ($_POST['email'] ?? '')),
            'password' => (string) ($_POST['password'] ?? ''),
        ];

        $validator = new Validator(
            $input,
            ['email' => 'required|email', 'password' => 'required'],
            ['email' => 'Alamat email', 'password' => 'Kata sandi']
        );

        if ($validator->fails()) {
            $this->redirectWithErrors(self::LOGIN_PATH, $validator->errors(), ['email' => $input['email']]);
        }

        $email = mb_strtolower($input['email']);
        $user = (new User())->findByEmail($email);

        // Pesan disamakan agar tidak membocorkan email mana yang terdaftar.
        if ($user === null || !password_verify($input['password'], $user['password_hash'])) {
            $this->redirectWithErrors(self::LOGIN_PATH, ['email' => 'Email atau kata sandi salah.'], ['email' => $input['email']]);
        }

        if (!(bool) $user['is_active']) {
            $this->redirectWithErrors(self::LOGIN_PATH, ['email' => 'Akun ini sedang dinonaktifkan.'], ['email' => $input['email']]);
        }

        Auth::login($user);
        $this->redirect('/');
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }

        $this->view('auth/register', [
            'errors' => Session::pullFlash('errors', []),
            'old'    => Session::pullFlash('old', []),
            'csrf'   => Csrf::token(),
        ]);
    }

    public function register(): void
    {
        if (!Csrf::verify($_POST['_token'] ?? null)) {
            $this->redirectWithErrors(self::REGISTER_PATH, ['_token' => 'Sesi formulir kedaluwarsa. Silakan coba lagi.']);
        }

        $input = [
            'name'     => trim((string) ($_POST['name'] ?? '')),
            'email'    => trim((string) ($_POST['email'] ?? '')),
            'password' => (string) ($_POST['password'] ?? ''),
        ];

        $validator = new Validator(
            $input,
            [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255',
                'password' => 'required|min:8',
            ],
            ['name' => 'Nama lengkap', 'email' => 'Alamat email', 'password' => 'Kata sandi']
        );

        $old = ['name' => $input['name'], 'email' => $input['email']];

        if ($validator->fails()) {
            $this->redirectWithErrors(self::REGISTER_PATH, $validator->errors(), $old);
        }

        $email = mb_strtolower($input['email']);
        $users = new User();

        if ($users->findByEmail($email) !== null) {
            $this->redirectWithErrors(self::REGISTER_PATH, ['email' => 'Email sudah terdaftar.'], $old);
        }

        $users->create(
            $input['name'],
            $email,
            password_hash($input['password'], PASSWORD_DEFAULT),
            'buyer'
        );

        Session::flash('success', 'Akun berhasil dibuat. Silakan masuk.');
        $this->redirect(self::LOGIN_PATH);
    }

    public function logout(): void
    {
        Auth::logout();
        Session::flash('success', 'Anda telah keluar.');
        $this->redirect(self::LOGIN_PATH);
    }
}
