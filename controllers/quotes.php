<?php

namespace Controllers;

use Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class Quotes
{
    public function random(Request $request, Application $app) {
        return "<html>lol</html>";  
    }

    public function renderForm(Request $request, Application $app) {
        return $app['twig']->render('quote_submit.html');
    }

    public function submitForm(Request $request, Application $app) {
        $db = $app['db'];
        $db->insert('qdb_quotes', array(
            'quote' => $request->get('quote')
        ));

        $newRowId = $db->lastInsertId();

        return $app->redirect("/quotes/$newRowId");
    }
}
