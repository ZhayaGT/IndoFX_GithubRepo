<?php

/** @var \IndoFX\Core\Router $router */

// Mendaftarkan URL beranda
$router->add('GET', '/', 'HomeController', 'index');

// Rute Autentikasi
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');

$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');

$router->add('GET', '/logout', 'AuthController', 'logout');