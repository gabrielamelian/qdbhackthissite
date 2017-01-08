<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../config.php';
require_once __DIR__.'/../controllers/quotes.php';

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/quotes/random', 'Controllers\\Quotes::random');

$app->get('/quotes/submit', 'Controllers\\Quotes::renderForm');
$app->post('/quotes/submit', 'Controllers\\Quotes::submitForm');

//Homepage redirects to the quote route
$app->get('/', function () use ($app) {
    return $app->redirect('/quotes/random');
});

return $app;
