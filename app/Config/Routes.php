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
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard/calendar-data', 'Dashboard::getCalendarData');
$routes->get('dashboard/chart-data', 'Dashboard::getChartData');
$routes->get('dashboard/statistics', 'Dashboard::getStatistics');

/**
 * Routes: Program Kerja
 * Routing untuk modul Program Kerja Pengawasan Tahunan (PKPT)
 */
$routes->group('program-kerja', function($routes) {
    $routes->get('/', 'ProgramKerja::index');
    $routes->get('tambah', 'ProgramKerja::tambah');
    $routes->post('simpan', 'ProgramKerja::simpan');
    $routes->get('lihat/(:num)', 'ProgramKerja::lihat/$1');
    $routes->get('edit/(:num)', 'ProgramKerja::edit/$1');
    $routes->post('perbarui/(:num)', 'ProgramKerja::perbarui/$1');
    $routes->post('hapus/(:num)', 'ProgramKerja::hapus/$1');
    $routes->get('detail/(:num)', 'ProgramKerja::detail/$1');
    // Document Management Routes
    $routes->get('dokumen/(:num)', 'ProgramKerja::dokumen/$1');
    $routes->post('upload-dokumen/(:num)', 'ProgramKerja::uploadDokumen/$1');
    $routes->delete('hapus-dokumen/(:num)', 'ProgramKerja::hapusDokumen/$1');
    $routes->get('download/(:num)', 'ProgramKerja::download/$1');
    $routes->get('preview/(:num)', 'ProgramKerja::preview/$1'); // Added preview route
    $routes->get('export-excel', 'ProgramKerja::exportExcel');
});
