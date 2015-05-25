<?php


class Mobac_Connection extends PHPUnit_Framework_TestCase
{
    public function testDBConnection()
    {

        //  TODO Specifi db_name in phpunit config

        \Mobac\Config::setParam(['db_name' => $GLOBALS['db_name']]);
        $dbh = \Mobac\Connection::getInstance();

        $this->assertInstanceOf('MongoDB', $dbh, 'Connection not estableshed');
    }

    /**
     * @expectedException \Mobac\Exception\DBNameNotSpecified
     */
    public function testDBNameNotSpecifiedException()
    {
        \Mobac\Connection::close();
        \Mobac\Config::unsetParam('db_name');
        \Mobac\Connection::getInstance();
    }
}
