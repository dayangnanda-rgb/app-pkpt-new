<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

/**
 * Routes: Dashboard
 * Dashboard utama aplikasi PKPT
 */
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptUser');
$routes->get('login/admin', 'Auth::loginAdmin');
$routes->post('login/admin', 'Auth::attemptAdmin');
$routes->get('login/auditor', 'Auth::loginAuditor');
$routes->post('login/auditor', 'Auth::attemptAuditor');
$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard/admin', 'Dashboard::admin', ['filter' => 'admin']);
$routes->get('dashboard/auditor', 'Dashboard::auditor', ['filter' => 'auditor']);
$routes->get('dashboard/user', 'Dashboard::user', ['filter' => 'user']);

$routes->get('dashboard/calendar-data', 'Dashboard::getCalendarData');
$routes->get('dashboard/chart-data', 'Dashboard::getChartData');
$routes->get('dashboard/statistics', 'Dashboard::getStatistics');

/**
 * Routes: Program Kerja
 * Routing untuk modul Program Kerja Pengawasan Tahunan (PKPT)
 */
$routes->group('program-kerja', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'ProgramKerja::index');
    $routes->get('lihat/(:num)', 'ProgramKerja::lihat/$1');
    $routes->get('detail/(:num)', 'ProgramKerja::detail/$1');
    $routes->get('preview/(:num)', 'ProgramKerja::preview/$1');
    $routes->get('download/(:num)', 'ProgramKerja::download/$1');
    $routes->get('unduh-dokumen/(:num)', 'ProgramKerja::unduhDokumen/$1');
    $routes->get('export-excel', 'ProgramKerja::exportExcel');

    // Let Controller handle granular access via checkAccess
    $routes->get('tambah', 'ProgramKerja::tambah');
    $routes->post('simpan', 'ProgramKerja::simpan');
    $routes->get('edit/(:num)', 'ProgramKerja::edit/$1');
    $routes->post('perbarui/(:num)', 'ProgramKerja::perbarui/$1');
    $routes->post('hapus/(:num)', 'ProgramKerja::hapus/$1');
    
    // Document Management
    $routes->get('dokumen/(:num)', 'ProgramKerja::dokumen/$1');
    $routes->post('upload-dokumen/(:num)', 'ProgramKerja::uploadDokumen/$1');
    $routes->delete('hapus-dokumen/(:num)', 'ProgramKerja::hapusDokumen/$1');
    // Approval & Review
    $routes->match(['get', 'post'], 'setujui/(:num)', 'ProgramKerja::setujui/$1');
    $routes->get('batalSetujui/(:num)', 'ProgramKerja::batalSetujui/$1');
});
