<?php


class Mobac_Config extends PHPUnit_Framework_TestCase
{
    public function testSettingLibraryParam()
    {
        \Mobac\Config::setParam(['key' => 1]);

        $expectedValue = 1;
        $this->assertEquals(\Mobac\Config::getParam('key'), $expectedValue);
    }

    public function testUnSettingLibraryParam()
    {
        \Mobac\Config::unsetParam('key');

        $expectedValue = null;
        $this->assertEquals(\Mobac\Config::getParam('key'), $expectedValue);
    }
}
