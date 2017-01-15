<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../includes/BaseTest.php';

class VoteTest extends BaseTest {

    private $quoteId = 1337;

    public function testUpvote() {
        $client = $this->createClient();
        $crawler = $client->request('POST', "/quotes/{$this->quoteId}/vote", array(
            'qid' => $this->quoteId,
            'value' => 'upvote'
        ));

        $quoteArray = $this->db->fetchAssoc("SELECT * FROM qdb_quotes WHERE id = ?", 
            array($this->quoteId));

        $this->assertEquals(1001, $quoteArray['score']);
        $this->assertEquals(1201, $quoteArray['votes']);
    }

}
