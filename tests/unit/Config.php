<?php


class Mongoob_Config extends PHPUnit_Framework_TestCase
{
    public function testSettingLibraryParam()
    {
        \Mongoob\Config::setParam(['key' => 1]);

        $expectedValue = 1;
        $this->assertEquals(\Mongoob\Config::getParam('key'), $expectedValue);
    }

    public function testUnSettingLibraryParam()
    {
        \Mongoob\Config::unsetParam('key');

        $expectedValue = null;
        $this->assertEquals(\Mongoob\Config::getParam('key'), $expectedValue);
    }
}
