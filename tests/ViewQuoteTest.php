<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

class ViewQuoteTest extends BaseTest {

    public function testViewQuote() {
      $this->seedDB();
      $client = $this->createClient();
      $crawler = $client->request('GET', '/quotes/1337');

      $this->assertTrue($client->getResponse()->isOk(), 'Response from quotes bottom50 is ok');
      //$this->assertCount(50, $crawler->filter('div.quotebox'), 'Page contains 50 divs with the class "quotebox"');
      //$this->assertCount(50, $crawler->filter('p.quotetext'), 'Page contains 50 p tags with the class "quotetext"');
      $this->assertCount(0, $crawler->filter('p.quotetext:contains("quote leet")'));
    }

    public function testNotFoundQuote() {
        $this->assertTrue(false);
    }

    public function testUnapprovedQuote() {
        $this->assertTrue(false);
    }

}
