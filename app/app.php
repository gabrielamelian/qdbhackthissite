<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../config.php';
require_once __DIR__.'/../controllers/quotes.php';

// Config
use Silex\Provider\FormServiceProvider;

$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());


// Routes
$app->get('/quotes/random', 'Controllers\\Quotes::random');
$app->get('/', function () use ($app) {
    return $app->redirect('/quotes/random');
});
$app->match('/quotes/submit', 'Controllers\\Quotes::submit');

return $app;
