<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;

class AuthController extends Controller
{
    public function login()
    {
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

    public function processLogin()
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $user = (new User())->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            $this->redirect('/');
        }

        $_SESSION['login_error'] = 'Email atau password salah!';
        $_SESSION['login_old'] = ['email' => $email];

        $this->redirect('/login');
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }

        $this->view('auth/register', [
            'error' => $_SESSION['register_error'] ?? null,
            'old'   => $_SESSION['register_old'] ?? [],
        ]);

        unset($_SESSION['register_error'], $_SESSION['register_old']);
    }

    public function processRegister()
    {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $model = new User();

        if ($model->findByEmail($email)) {
            $_SESSION['register_error'] = 'Email sudah terdaftar!';
            $_SESSION['register_old'] = ['name' => $name, 'email' => $email];

            $this->redirect('/register');
        }

        $model->create($name, $email, password_hash($password, PASSWORD_BCRYPT), 'buyer');

        $_SESSION['login_success'] = 'Registrasi berhasil! Silakan masuk.';
        $_SESSION['login_old'] = ['email' => $email];

        $this->redirect('/login');
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        $this->redirect('/login');
    }
}
