<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
// $routes->get('getTtvRanap/(:any)', 'Pasien::getTtvRanap/$1');

$routes->group('pasien', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Pasien::index'); 
    $routes->match(['get', 'post'], 'getAllRiwayat', 'Pasien::handleAllRiwayat');
    $routes->match(['get', 'post'], 'getPasien', 'Pasien::handleAllgetPasien');
    $routes->match(['get', 'post'], 'dokterList', 'Pasien::handleAlldokterList');
    $routes->match(['get', 'post'], 'saveCatatan_perawatan', 'Pasien::handleAllsaveCatatan_perawatan');
    $routes->match(['get', 'post'], 'updateCatatan', 'Pasien::handleAllupdateCatatan');
    $routes->match(['get', 'post'], 'deleteCatatan', 'Pasien::handleAlldeleteCatatan');
    $routes->match(['get', 'post'], 'findCatatan', 'Pasien::handleAllfindCatatan');
    $routes->match(['get', 'post'], 'getCatatan', 'Pasien::handleAllgetCatatan');
    $routes->match(['get', 'post'], 'riwayatSOAPIE', 'Pasien::handleAllriwayatSOAPIE');
    $routes->match(['get', 'post'], 'getRiwayatObat', 'Pasien::handleAllgetRiwayatObat');
    $routes->match(['get', 'post'], 'getGambarRadiologi', 'Pasien::handleAllgetGambarRadiologi');
    $routes->match(['get', 'post'], 'getLab', 'Pasien::handleAllgetLab');
});


$routes->get('/', 'Login::index');  
$routes->get('login', 'Login::index');  
$routes->get('logout', 'Login::logout');  

$routes->post('AuthController/checklogin', 'AuthController::checklogin');