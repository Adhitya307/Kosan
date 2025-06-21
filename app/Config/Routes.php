<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->get('/', 'Auth::login');

$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::process_register');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::process_login');
$routes->get('/logout', 'Auth::logout');
$routes->post('/logout', 'Auth::logout');


$routes->get('/dashboard', 'Dashboard::index');

$routes->get('/google-login', 'GoogleAuth::redirect');
$routes->get('/google-callback', 'GoogleAuth::callback');

$routes->get('/atur-password', 'Auth::setPassword');
$routes->post('/atur-password', 'Auth::savePassword');

$routes->get('/forgot-password', 'Auth::forgotPasswordForm');
$routes->post('/forgot-password', 'Auth::sendResetToken');
$routes->get('/reset-password', 'Auth::resetPasswordForm');
$routes->post('/reset-password', 'Auth::updatePasswordFromToken');

$routes->get('customer', 'Customer::index');
$routes->get('customer/tambah', 'Customer::create');    // ganti create jadi tambah
$routes->post('customer/store', 'Customer::store');
$routes->get('customer/edit/(:segment)', 'Customer::edit/$1');
$routes->post('customer/update/(:segment)', 'Customer::update/$1');
$routes->get('customer/delete/(:segment)', 'Customer::delete/$1');

$routes->get('/kamar', 'Kamar::index');
$routes->get('/kamar/create', 'Kamar::create');
$routes->post('/kamar/store', 'Kamar::store');
$routes->get('/kamar/edit/(:num)', 'Kamar::edit/$1');
$routes->post('/kamar/update/(:num)', 'Kamar::update/$1');
$routes->post('/kamar/delete/(:num)', 'Kamar::delete/$1');
$routes->put('kamar/update/(:num)', 'Kamar::update/$1');

$routes->get('booking/(:num)', 'BookingController::index/$1');    // Menampilkan form booking
$routes->post('booking/process', 'BookingController::process');  // Proses booking
$routes->get('booking/success/(:num)', 'BookingController::success/$1');  // Halaman sukses

$routes->get('booking/kelolaboking', 'BookingController::kelolabooking');
$routes->post('booking/updateStatus', 'BookingController::updateStatus');

$routes->get('informasi_booking', 'BookingController::informasiBooking');

// Transaksi Pembayaran
$routes->get('transaksi/create/(:num)', 'TransaksiController::create/$1');
$routes->post('transaksi/store', 'TransaksiController::store');
$routes->get('transaksi/sukses', 'TransaksiController::sukses');

$routes->post('transaksi/upload_bukti', 'TransaksiController::upload_bukti');

$routes->post('customer/delete/(:num)', 'Customer::delete/$1');
