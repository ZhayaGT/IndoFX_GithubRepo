<?php

/** @var \IndoFX\Core\Router $router */

// Mendaftarkan URL Landing Page
$router->add('GET', '/', 'HomeController', 'index');

// Rute Autentikasi (Mendukung /login dan /auth/login)
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');

$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');

$router->add('GET', '/logout', 'AuthController', 'logout');