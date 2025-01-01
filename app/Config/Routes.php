<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('getTtvRanap/(:any)', 'Pasien::getTtvRanap/$1');