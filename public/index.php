<?php

spl_autoload_register(function ($class_name) {
    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . $class_name . '.php';
    $path = str_replace('\\', '/', $path);
    include($path);
});
include '../config/app.php';

$query_builder = new \Database\Builders\MySqlBuilder();

$url = $_SERVER['REQUEST_URI'];

$router->resolve($url, $_SERVER['REQUEST_METHOD']);
