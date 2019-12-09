<?php

namespace Xi\Netvisor;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;

class XmlTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    public function setUp()
    {
        $builder = SerializerBuilder::create();
        $builder->setPropertyNamingStrategy(new LowercaseNamingStrategy());

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

        if (is_int($value)) {
            $this->assertContains(sprintf('>%s</%s>', $value, $tag), $xml);
            return;
        }

        $this->assertContains(sprintf('><![CDATA[%s]]></%s>', $value, $tag), $xml);
    }

    /**
     * @param string $tag
     * @param string $xml
     */
    public function assertXmlDoesNotContainTag($tag, $xml)
    {
        $this->assertNotContains(sprintf('<%s', $tag), $xml);
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
