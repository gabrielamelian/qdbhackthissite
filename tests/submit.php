<?php

require_once __DIR__.'/../controllers/main.php';

use Controllers\Main;

class ContactFormTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {
        echo "test setUp\n";
    }

    public function tearDown() {
        echo "test tearDown\n";
    }

    public function testDisplaysForm() {
      $this->assertTrue(false);
    }

    public function testSubmit() {

    }
}
