<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Welcome Page as default after login
$routes->get('/welcomepage', 'WelcomeController::index');

// Optional: Make welcome page the homepage
// $routes->get('/', 'WelcomeController::index');

$routes->get('/', 'Home::index');
$routes->get('/databasetest', 'DatabaseTest::index');

// Movie Routes
$routes->get('/movies', 'MovieController::index');
$routes->get('/movie-detail/(:num)', 'MovieController::detail/$1');
$routes->post('/save-movie-and-redirect', 'MovieController::saveMovieAndRedirect');

// User Routes
$routes->get('/register', 'UserController::register');
$routes->post('/store', 'UserController::store');
$routes->get('/login', 'UserController::login');
$routes->post('/login/authenticate', 'UserController::authenticate');
$routes->get('/logout', 'UserController::logout');

// Restricted Routes
$routes->get('/add-movie', 'MovieController::addMovie');
$routes->post('/save-movie', 'MovieController::saveMovie');
$routes->post('/save-review', 'MovieController::saveReview');

// Review Routes
$routes->post('/edit-review/(:num)', 'MovieController::editReview/$1');
$routes->get('/delete-review/(:num)', 'MovieController::deleteReview/$1');
