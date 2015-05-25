<?php

use Mobac\Collection;

/**
 * Test on behalf of vendor collection.
 */
class Mobac_Record extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        \Mobac\Config::setParam(['db_name' => $GLOBALS['db_name']]);

    }

    public function setUp()
    {
        (new Collection\TestCollection())->getDB()->drop();
    }
    
    public function testErrorOnInsertionWithoutRequiredFields()
    {
        return true;
    }


    public static function tearDownAfterClass()
    {
        //(new \Mobac\Collection\Vendor)->getDB()->drop();
    }
}
