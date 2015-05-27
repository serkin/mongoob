<?php


class Mongoob_Connection extends PHPUnit_Framework_TestCase
{
    public function testDBConnection()
    {

        //  TODO Specifi db_name in phpunit config

        \Mongoob\Config::setParam(['db_name' => $GLOBALS['db_name']]);
        $dbh = \Mongoob\Connection::getInstance();

        $this->assertInstanceOf('MongoDB', $dbh, 'Connection not estableshed');
    }

    /**
     * @expectedException \Mongoob\Exception\DBNameNotSpecified
     */
    public function testDBNameNotSpecifiedException()
    {
        \Mongoob\Connection::close();
        \Mongoob\Config::unsetParam('db_name');
        \Mongoob\Connection::getInstance();
    }
}
