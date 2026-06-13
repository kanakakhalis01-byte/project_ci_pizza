<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Routes Autentikasi
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::processLogin');
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::processRegister');
$routes->get('/logout', 'Auth::logout');

// Routes Cart (Keranjang)
$routes->post('/cart/add', 'Cart::add');
$routes->post('/cart/update', 'Cart::update');
$routes->get('/cart/remove/(:num)', 'Cart::remove/$1');

// Routes Transaksi
$routes->post('/checkout', 'Transaction::checkout');
$routes->get('/transactions', 'Transaction::index');

// Payment
$routes->get('/transactions/pay/(:num)', 'Transaction::pay/$1');
$routes->post('/transactions/upload-proof/(:num)', 'Transaction::uploadProof/$1');

// Grouping Route khusus Admin
$routes->group('admin', function($routes) {
    $routes->get('login', 'Admin::login');
    $routes->post('login', 'Admin::processLogin');
    $routes->get('logout', 'Admin::logout');
    
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('transactions', 'Admin::transactions');
    $routes->post('transactions/update-status/(:num)', 'Admin::updateTransactionStatus/$1');
    
    // Rute CRUD Produk 
    $routes->get('products', 'Admin::products');
    $routes->post('products/create', 'Admin::productCreate');
    $routes->post('products/update/(:num)', 'Admin::productUpdate/$1');
    $routes->get('products/delete/(:num)', 'Admin::productDelete/$1');
});