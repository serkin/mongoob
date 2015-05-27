<?php

use Mongoob\Collection;

/**
 * Test on behalf of vendor collection.
 */
class Mongoob_Record extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        \Mongoob\Config::setParam(['db_name' => $GLOBALS['db_name']]);

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
        return true;
    }
}
