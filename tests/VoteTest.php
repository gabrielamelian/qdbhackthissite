<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class VoteTest extends BaseTest {

    private $quoteId = 1337;
    private $ipAddress = '1.3.3.7';

    public function setUp() {
        $_SERVER['REMOTE_ADDR'] = $this->ipAddress;
        parent::setUp();
    }

    private function getQuote() {
        $quoteArray = $this->db->fetchAssoc("SELECT * FROM qdb_quotes WHERE id = ?", 
            array($this->quoteId));

        return $quoteArray;
    }

    private function getVote() {
        $voteArray = $this->db->fetchAssoc("SELECT * FROM qdb_votes WHERE ip = ? and qid = ?", [
            $this->ipAddress,
            $this->quoteId
        ]);

        return $voteArray;
    }

    private function upvote() {
        $client = $this->createClient();
        $crawler = $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'value' => 'upvote'
        ));

        return $client->getResponse()->getContent();
    }

    private function downvote() {
        $client = $this->createClient();
        $crawler = $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'value' => 'downvote'
        ));

        return $client->getResponse()->getContent();
    }

    public function testUpvote() {
        $this->upvote();

        $quoteArray = $this->getQuote();

        $this->assertEquals(1001, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testDownvote() {
        $this->downvote();

        $quoteArray = $this->getQuote();

        $this->assertEquals(999, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testInvalidAction() {
        $client = $this->createClient();
        $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'value' => 'lulz'
        ));

        $response = $client->getResponse();

        // shouldn't have updated.
        $this->assertEquals($response->getContent(), "1000/1200");
    }

    public function testInvalidQuoteId() {
        $notFound = false;
        try {
            $client = $this->createClient();
            $client->request('POST', "/quotes/999999/vote", array(
                'value' => 'upvote'
            ));
            $response = $client->getResponse();
        } catch(NotFoundHttpException $e) {
            $notFound = true;
            $message = $e->getMessage();
        }

        $this->assertTrue($notFound);
        $this->assertEquals($message, "Quote 999999 does not exist.");
    }

    public function testDoubleUpvoteCountsAsOne() {
        $firstResponse = $this->upvote();
        $secondResponse = $this->upvote();

        $quoteArray = $this->getQuote();

        $this->assertEquals($firstResponse, $secondResponse);

        $this->assertEquals(1001, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testDoubleDownvoteCountsAsOne() {
        $firstResponse = $this->downvote();
        $secondResponse = $this->downvote();
        $quoteArray = $this->getQuote();

        $this->assertEquals($firstResponse, $secondResponse);

        $this->assertEquals(999, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testChangeVoteOne() {
        $this->upvote();
        $this->downvote();

        $quoteArray = $this->getQuote();

        $this->assertEquals(999, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testChangeVoteTwo() {
        $this->downvote();
        $this->upvote();

        $quoteArray = $this->getQuote();

        $this->assertEquals(1001, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

    public function testStoresIPUpvote() {
        $this->upvote();

        $voteArray = $this->getVote();
        $this->assertNotFalse($voteArray);
        $this->assertEquals($voteArray['value'], 0);
    }

    public function testStoresIPDownvote() {
        $this->downvote();

        $voteArray = $this->getVote();
        $this->assertNotFalse($voteArray);
        $this->assertEquals($voteArray['value'], 1);
    }
}
