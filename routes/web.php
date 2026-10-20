<?php

/** @var \IndoFX\Core\Router $router */

// Mendaftarkan URL beranda
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/login', 'AuthController', 'login');