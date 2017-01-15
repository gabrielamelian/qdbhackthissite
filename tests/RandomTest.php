<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

class IndexRandom extends BaseTest {

    public function testRedirect() {
      $this->seedDB();
      $client = $this->createClient();
      $crawler = $client->request('GET', '/');

      $this->assertTrue($client->getResponse()->isRedirect('/quotes/random'),
    'response is a redirect to /quotes/random');
    }

    public function testRandom() {
      $this->seedDB();
      $client = $this->createClient();
      $crawler = $client->request('GET', '/quotes/random');

      $this->assertTrue($client->getResponse()->isOk(), 'Response from quotes random is ok');
      $this->assertCount(50, $crawler->filter('div.quotebox'), 'Page contains 50 divs with the class "quotebox"');
    }

}
