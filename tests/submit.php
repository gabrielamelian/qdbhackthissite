<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/base.php';

class SubmitTest extends BaseTest {

    public function testAddsQuote() {
      $client = $this->createClient();
      $crawler = $client->request('POST', '/quotes/submit', array(
          'quote' => 'me> i can internetz\n',
      ));

      echo $client->getResponse();

      //$this->assertTrue($client->getResponse()->isOk());
      //$this->assertCount(1, $crawler->filter('html:contains("Hello gabita")'));
    }

}
