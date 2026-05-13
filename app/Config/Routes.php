<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Book::index');
$routes->get('/detail/(:segment)', 'Book::detail/$1');
$routes->get('/read/(:segment)', 'Book::read/$1');