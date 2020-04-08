<?php

use lib\routes\Router;

$router = new Router();

$router->get('/', 'Controllers\ClansController@index');
$router->get('/clans/:id', 'Controllers\ClansController@index');