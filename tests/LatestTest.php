<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

class LatestTest extends BaseTest {

    public function testBottom50() {
      $this->seedDB();
      $client = $this->createClient();
      $crawler = $client->request('GET', '/quotes/latest');

      $this->assertTrue($client->getResponse()->isOk(), 'Response from quotes bottom50 is ok');
      $this->assertCount(50, $crawler->filter('div.quotebox'), 'Page contains 50 divs with the class "quotebox"');
      $this->assertCount(50, $crawler->filter('p.quotetext'), 'Page contains 50 p tags with the class "quotetext"');
      $this->assertCount(0, $crawler->filter('p.quotetext:contains("This is a quote that has not been approved")'));
    }

}
