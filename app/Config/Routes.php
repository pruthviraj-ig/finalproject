<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/databasetest', 'DatabaseTest::index');
$routes->get('/movies', 'MovieController::index');
$routes->get('/add-movie', 'MovieController::addMovie');
$routes->post('/save-movie', 'MovieController::saveMovie');

