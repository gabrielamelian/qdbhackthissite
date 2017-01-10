<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/base.php';

class SubmitTest extends BaseTest {

    private $quoteContents = 'me> i can internetz\n';

    private function fakeSubmit() {
        $client = $this->createClient();
        $crawler = $client->request('POST', '/quotes/submit', array(
            'form' => array(
                'quote' => $this->quoteContents
            )
        ));

        return array($client, $crawler);
    }

    public function testAddsQuote() {
        $firstCount = $this->countTable('qdb_quotes');
        list($client, $crawler) = $this->fakeSubmit(); 
        $secondCount = $this->countTable('qdb_quotes');

        $this->assertEquals($secondCount, $firstCount + 1);
    }

    public function testRedirect() {
        list($client, $crawler) = $this->fakeSubmit(); 

        $response = $client->getResponse();
        $redirectLocation = $response->headers->get('location');

        $this->assertTrue($response->isRedirect());
        $this->assertTrue(strpos($redirectLocation, "/quotes/") === 0);
    }

    public function testDataAdded() {
        list($client, $crawler) = $this->fakeSubmit(); 

        $response = $client->getResponse();
        $redirectLocation = $response->headers->get('location');
        $quoteId = split("/", $redirectLocation)[2];

        $quoteArray = $this->db->fetchAssoc("SELECT * FROM qdb_quotes WHERE id = ?", 
            array($quoteId));

        $this->assertEquals($quoteArray['score'], 0);
        $this->assertEquals($quoteArray['votes'], 0);
        $this->assertEquals($quoteArray['status'], 0);
        $this->assertEquals($quoteArray['quote'], $this->quoteContents);
    }

    public function testDisplaysForm() {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/quotes/submit');

        $this->assertCount(1, $crawler->filter('form'));
        $this->assertCount(1, $crawler->filter('textarea[id=form_quote]'));
    }

    public function testBlankQuoteFails() {
        $this->assertTrue(false);
    }

}
