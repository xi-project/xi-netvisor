<?php

namespace Xi\Netvisor;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class XmlTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function setUp()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @param  Object $object
     * @return string
     */
    public function toXml($object)
    {
        return $this->serializer->serialize($object, 'xml');
    }
}