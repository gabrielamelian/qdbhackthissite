<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../config.php';
require_once __DIR__.'/../includes/MatchesCaptcha.php';
require_once __DIR__.'/../includes/VoteIsValid.php';
require_once __DIR__.'/../includes/quote.php';
require_once __DIR__.'/../controllers/quotes.php';

// Config
$app['debug'] = true;

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
    'twig.options' => array(
        'autoescape' => 'html',
    ),
    'twig.form.templates' => array(
        'bootstrap_3_layout.html.twig',
        'forms_override.html'
    ),
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));
$app['converter.quote'] = function () use ($app) {
    return new QuoteConverter($app);
};

if(!defined('RUNNING_UNIT_TESTS')) {
    $app->register(new Silex\Provider\CsrfServiceProvider());
}

// Routes
$app->get('/quotes/random', 'Controllers\\Quotes::random');
$app->get('/', function () use ($app) {
    return $app->redirect('/quotes/random');
});
$app->get('/quotes/top50', 'Controllers\\Quotes::top50');
$app->get('/quotes/bottom50', 'Controllers\\Quotes::bottom50');
$app->get('/quotes/latest', 'Controllers\\Quotes::latest');
$app->match('/quotes/submit', 'Controllers\\Quotes::submit');
$app->get('/quotes/captcha.png', 'Controllers\\Quotes::captcha');
$app->post('/quotes/{quote}/vote', 'Controllers\\Quotes::vote')
    ->convert('quote', 'converter.quote:convert');

return $app;
