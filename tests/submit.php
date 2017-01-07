<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/base.php';

class SubmitTest extends BaseTest {
    public function testHelloWorld() {
      $client = $this->createClient();
      $crawler = $client->request('GET', '/hello/gabita');

      $this->assertTrue($client->getResponse()->isOk());
      $this->assertCount(1, $crawler->filter('html:contains("Hello gabita")'));
    }

}
