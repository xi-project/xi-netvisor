<?php

namespace Xi\Netvisor\Component;

use JMS\Serializer\Metadata\PropertyMetadata;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;

class LowercaseNamingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideNames
     */
    public function lowercases($name)
    {
        $strategy = new LowercaseNamingStrategy();

        $toBeObject = array();
        $toBeObject[$name] = '';
        $object = (object)$toBeObject;

        $metadata = new PropertyMetadata($object, $name);

        $this->assertEquals(strtolower($name), $strategy->translateName($metadata));
    }

    public function provideNames()
    {
        return array(
            array('camelCase'),
            array('CamelCaps'),
            array('CAPSLOCKCAPSLOCK'),
        );
    }
}
