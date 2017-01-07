<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

namespace Controllers
{
    class Quotes
    {
        public function random(Request $request, Application $app) {
          
        }

        public function renderForm(Request $request, Application $app) {
            var_dump($request);
        }

        public function submitForm() {
            //var_dump($request);
            return 'lol';
        }
    }
}
