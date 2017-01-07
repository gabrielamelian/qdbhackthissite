<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/base.php';

class SubmitTest extends BaseTest {

    /**
     * Internal utility function for submitting quotes for testing.
     * @return $crawler the crawler.
     */
    private function submitQuote() {
        $client = $this->createClient();
        $crawler = $client->request('POST', '/quotes/submit', array(
            'quote' => 'me> i can internetz\n',
        ));

        return $crawler;
    }

    public function testAddsQuote() {
        $firstCount = $this->countTable('qdb_quotes');
        $this->submitQuote();
        $secondCount = $this->countTable('qdb_quotes');

        $this->assertEquals($secondCount, $firstCount + 1);
    }

}
