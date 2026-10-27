<?php

/** @var \IndoFX\Core\Router $router */

$router->add('GET', '/', 'HomeController', 'index');

$router->add('GET', '/catalog/explore', 'CatalogController', 'explore');

$router->add('GET', '/asset/detail', 'AssetController', 'detail');

$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');

$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');

$router->add('GET', '/logout', 'AuthController', 'logout');
