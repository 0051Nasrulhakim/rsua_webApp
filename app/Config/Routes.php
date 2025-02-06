<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
// $routes->get('getTtvRanap/(:any)', 'Pasien::getTtvRanap/$1');

$routes->group('pasien', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Pasien::index');
    $routes->get('getPasien', 'Pasien::getPasien');
    $routes->get('getAllRiwayat', 'Pasien::getAllRiwayat');
    $routes->get('dokterList', 'Pasien::dokterList');

    $routes->match(['get', 'post'], 'saveCatatan_perawatan', 'Pasien::saveCatatan_perawatan');
    $routes->match(['get', 'post'], 'updateCatatan', 'Pasien::updateCatatan');
    $routes->match(['get', 'post'], 'deleteCatatan', 'Pasien::deleteCatatan');
    $routes->match(['get', 'post'], 'findCatatan', 'Pasien::findCatatan');
    $routes->match(['get', 'post'], 'getCatatan', 'Pasien::getCatatan');
    $routes->match(['get', 'post'], 'riwayatSOAPIE', 'Pasien::riwayatSOAPIE');
    $routes->match(['get', 'post'], 'getRiwayatObat', 'Pasien::getRiwayatObat');
    $routes->match(['get', 'post'], 'getGambarRadiologi', 'Pasien::getGambarRadiologi');
    $routes->match(['get', 'post'], 'getLab', 'Pasien::getLab');
});

$routes->group('obat', ['filter' => 'auth'], function($routes) {

    $routes->match(['get', 'post'], 'getRiwayatObat', 'Obat::getRiwayatObat');
    $routes->match(['get', 'post'], 'getStokObatPasien', 'Obat::getStokObatPasien');
    $routes->match(['get', 'post'], 'getStokObatPasienOld', 'Obat::getStokObatPasienOld');
    $routes->match(['get', 'post'], 'simpanPemberianObat', 'Obat::simpanPemberianObat');
    $routes->match(['get', 'post'], 'getWaktuByJam', 'Obat::getWaktuByJam');
    $routes->match(['get', 'post'], 'getWaktuByJam/(:any)', 'Obat::getWaktuByJam/$1');
    $routes->match(['get', 'post'], 'setStopObat', 'Obat::setStopObat');
    $routes->match(['get', 'post'], 'lanjutkanObat', 'Obat::lanjutkanObat');
    
});

$routes->match(['get', 'post'], 'obat/getCpo', 'Obat::getCpo');
$routes->match(['get', 'post'], 'obat/viewGetCpo', 'Obat::viewGetCpo');

$routes->group('HandOver', ['filter' => 'auth'], function($routes) {
    $routes->match(['get', 'post'], '/', 'HandOver::index');
    $routes->match(['get', 'post'], 'handOverPerTanggal', 'HandOver::handOverPerTanggal');
    $routes->match(['get', 'post'], 'detailCatatan', 'HandOver::detailCatatan');
    
});
// $routes->get('/', 'Pasien::index');
$routes->get('login', 'Login::index');
$routes->get('/', 'Login::index');
$routes->get('login/logout', 'Login::logout');

$routes->get('AuthController/checklogin', 'AuthController::checklogin');
$routes->post('AuthController/checklogin', 'AuthController::checklogin');

$routes->get('AuthController/getDataLogin', 'AuthController::getDataLogin');
$routes->post('AuthController/getDataLogin', 'AuthController::getDataLogin');

$routes->get('AuthController/getDataLogin/(:any)', 'AuthController::getDataLogin/$1');
$routes->post('AuthController/getDataLogin/(:any)', 'AuthController::getDataLogin/$1');
