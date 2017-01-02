<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__.'/../config.php';
require_once __DIR__.'/../controllers/main.php';

$app['debug'] = true;

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

// $app->get('/blog/{id}', function ($id) use ($app) {
//     $sql = "SELECT * FROM qdb_quotes WHERE id = ?";
//     $post = $app['db']->fetchAssoc($sql, array((int) $id));
//
//     return  "<h1>{$post['title']}</h1>".
//             "<p>{$post['body']}</p>";
// });

$app->run();
