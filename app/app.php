<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../config.php';
require_once __DIR__.'/../controllers/main.php';

$app['debug'] = true;

$app->get('/hello/{name}', function ($name) use ($app) {
    return '<html>Hello '.$app->escape($name).'</html>';
});

return $app;
