<?php

namespace Xi\Netvisor;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;

class XmlTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function setUp()
    {
        $builder = SerializerBuilder::create();
        $builder->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy());

        $this->serializer = $builder->build();
    }

    /**
     * @param  Object $object
     * @return string
     */
    public function toXml($object)
    {
        return $this->serializer->serialize($object, 'xml');
    }

    /**
     * @param string $tag
     * @param string $value
     * @param string $xml
     */
    public function assertXmlContainsTagWithValue($tag, $value, $xml)
    {
        $this->assertContains(sprintf('<%s', $tag), $xml);
        $this->assertContains(sprintf('><![CDATA[%s]]></%s>', $value, $tag), $xml); // TODO: Integers are not enclosed with CDATA.
    }

    /**
     * @param string $tag
     * @param string $value
     * @param string $xml
     */
    public function assertXmlContainsTagWithAttributes($tag, $attributes, $xml)
    {
        $attributeLine = '';

        foreach ($attributes as $key => $value) {
            $attributeLine .= sprintf(' %s="%s"', $key, $value);
        }

        $this->assertContains(sprintf('<%s%s>', $tag, $attributeLine), $xml);
    }
}
