<?php

namespace Controllers;

use Silex\Application;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class Quotes
{
    public function random(Request $request, Application $app) {
      
    }

    public function renderForm(Request $request, Application $app) {
        var_dump($request);
    }

    public function submitForm(Request $request, Application $app) {
        $db = $app['db'];
        $quote = $request->get('quote');

        $db->insert('qdb_quotes', array(
            'quote' => $quote
        ));

        return 'lol';

    }
}
