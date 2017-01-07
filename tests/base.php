<?php

use Silex\WebTestCase;

define('TESTING', true);

class BaseTest extends WebTestCase {

    private $testDB = 'TEST_DB_QDB';
    public $db = NULL;

    public function createApplication() {
        $app = require __DIR__.'/../app/app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        $this->db = $app['db'];

        return $app;
    }

    public function initSQL($file) {
      $sql = file_get_contents($file);
      $this->db->query($sql);
    }

    public function setUp() {
        parent::setUp();
        $this->databaseSetUp();
    }

    public function tearDown() {
        parent::tearDown();
        $this->databaseTearDown();
    }

    public function databaseSetUp($last = false) {
        try {
            $this->db->query("CREATE DATABASE {$this->testDB};");
            $this->db->query("USE {$this->testDB};");
        } catch(PDOException $exc) {
            // If temporary database already exists, delete and try again
            // only once.
            if(!$last) {
                $this->databaseTearDown();
                $this->databaseSetUp(true);
            } else {
                throw $exc;
            }
        }

        $this->initSQL(__DIR__.'/../schema.sql');
        $this->initSQL(__DIR__.'/seeds.sql');
    }

    public function seedDB() {
      for ($x = 0; $x <= 50; $x++){
        $this->db->insert('qdb_quotes', array('score' => $x + 20,
          'votes' => $x + 25,
          'status' => 1,
          'quote' => 'This is quote number' . $x
        ));
      }
    }

    public function databaseTearDown() {
        if($this->db) {
            $this->db->query("DROP DATABASE {$this->testDB}");
        }
    }
}