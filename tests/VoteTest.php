<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        $badRequest = false;
        try {
            $client = $this->createClient();
            $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
                'value' => 'lulz'
            ));
            $response = $client->getResponse();
        } catch(BadRequestHttpException $e) {
            $badRequest = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($badRequest);
        $this->assertEquals($message, "The value you selected is not a valid choice.");
    }

    public function testInvalidQuoteId() {

        $notFound = false;
        try {
            $client = $this->createClient();
            $client->request('POST', "/quotes/1245125125125/vote", array(
                'value' => 'upvote'
            ));
            $response = $client->getResponse();
        } catch(NotFoundHttpException $e) {
            $notFound = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($notFound);
        $this->assertEquals($message, "Quote 2147483647 does not exist.");
    }


    public function testRateLimiting() {
        $this->assertTrue(false);
    }

}
