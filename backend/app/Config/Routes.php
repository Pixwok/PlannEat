<?php

use App\Controllers\RecipesController;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('/recipes', [RecipesController::class, 'test']);

$routes->group('/api', ['namespace' => 'App\Controllers\API'], function($routes) {
    $routes->resource('recipes');
});
