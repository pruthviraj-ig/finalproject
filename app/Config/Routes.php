<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'MovieController::index');
$routes->get('/movies', 'MovieController::index');
$routes->post('/save-review', 'MovieController::saveReview');
$routes->post('/edit-review/(:num)', 'MovieController::editReview/$1');
$routes->get('/delete-review/(:num)', 'MovieController::deleteReview/$1');
$routes->post('/save-api-movie', 'MovieController::saveApiMovie');
$routes->get('/login', 'UserController::login');
$routes->post('/authenticate', 'UserController::authenticate');
$routes->get('/logout', 'UserController::logout');
$routes->get('/register', 'UserController::register');
$routes->post('/store', 'UserController::store');
