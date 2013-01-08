<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Applicationl;

$app->get('/', function() use ($app) {
    return 'Hello World!';
});

$app->run();