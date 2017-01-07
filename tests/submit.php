<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\WebTestCase;

class SubmitTest extends WebTestCase {

    public function createApplication() {
      $app = require __DIR__.'/../app/app.php';
      $app['debug'] = true;
      unset($app['exception_handler']);
      return $app;
    }

    public function testHelloWorld() {
      $client = $this->createClient();
      $crawler = $client->request('GET', '/hello/gabita');

      $this->assertTrue($client->getResponse()->isOk());
      $this->assertCount(1, $crawler->filter('html:contains("Hello gabita")'));
    }

}
