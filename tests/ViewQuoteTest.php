<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ViewQuoteTest extends BaseTest {

    public function testViewQuote() {
      $this->seedDB();
      $client = $this->createClient();
      $crawler = $client->request('GET', '/quotes/1337');

      $this->assertTrue($client->getResponse()->isOk(), 'Response from quotes bottom50 is ok');
      $this->assertCount(1, $crawler->filter('p.quotetext:contains("quote leet")'));
    }

    public function testNotFoundQuote() {
        $notFound = false;
        try {
            $client = $this->createClient();
            $client->request('GET', "/quotes/99999");
            $response = $client->getResponse();
        } catch(NotFoundHttpException $e) {
            $notFound = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($notFound);
        $this->assertEquals($message, "Quote 99999 does not exist.");
    }

    public function testUnapprovedQuote() {
        $accessDenied = false;
        try {
            $client = $this->createClient();
            $client->request('GET', "/quotes/1338");
            $response = $client->getResponse();
        } catch(UnauthorizedHttpException $e) {
            $accessDenied = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($accessDenied);

    }

}
