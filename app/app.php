<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__.'/../config.php';
require_once __DIR__.'/../controllers/quotes.php';

$app['debug'] = true;

$app->get('/quotes/random', 'Controllers\\Quotes::random');

//Homepage redirects to the quote route
$app->get('/', function () use ($app) {
    return $app->redirect('/quotes');
});

return $app;
