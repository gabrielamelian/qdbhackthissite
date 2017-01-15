<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

class VoteTest extends BaseTest {

    private $quoteId = 1337;

    public function testUpvote() {
        $client = $this->createClient();
        $crawler = $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'value' => 'upvote'
        ));

        $quoteArray = $this->db->fetchAssoc("SELECT * FROM qdb_quotes WHERE id = ?", 
            array($this->quoteId));

        $this->assertEquals(1001, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testDownvote() {
        $client = $this->createClient();
        $crawler = $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'value' => 'downvote'
        ));

        $quoteArray = $this->db->fetchAssoc("SELECT * FROM qdb_quotes WHERE id = ?", 
            array($this->quoteId));

        $this->assertEquals(999, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testInvalidAction() {
        $client = $this->createClient();
        $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'value' => 'lulz'
        ));

        $response = $client->getResponse();

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Invalid action.", $response->getContent());
    }

    public function testInvalidQuoteId() {
        $client = $this->createClient();
        $client->request('POST', "/quotes/1245125125125/vote", array(
            'value' => 'upvote'
        ));

        $response = $client->getResponse();

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals("Invalid quote id.", $response->getContent());
    }


    public function testRateLimiting() {
        $this->assertTrue(false);
    }

}
