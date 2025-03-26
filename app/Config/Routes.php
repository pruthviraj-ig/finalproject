<?php
 
 use CodeIgniter\Router\RouteCollection;
 
 /**
  * @var RouteCollection $routes
  */
 $routes->get('/', 'Home::index');
 $routes->get('/databasetest', 'DatabaseTest::index');
 $routes->get('/movies', 'MovieController::index');  // View Movie List (Everyone)
 $routes->get('/register', 'UserController::register');
 $routes->post('/store', 'UserController::store');
 $routes->get('/login', 'UserController::login');
 $routes->post('/authenticate', 'UserController::authenticate');
 $routes->get('/logout', 'UserController::logout');
 
 /*
  * Restricted Routes (Only for Logged-In Users)
  */
 $routes->get('/add-movie', 'MovieController::addMovie');
 $routes->post('/save-movie', 'MovieController::saveMovie');
 $routes->post('/save-review', 'MovieController::saveReview');
 
 // Edit and Delete Review Routes
 $routes->post('/edit-review/(:num)', 'MovieController::editReview/$1');
 $routes->get('/delete-review/(:num)', 'MovieController::deleteReview/$1');
 
 