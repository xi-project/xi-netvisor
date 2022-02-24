<?php

namespace Xi\Netvisor;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;
use PHPUnit\Framework\TestCase;

class XmlTestCase extends TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Validate
     */
    private $validate;

    public function setUp(): void
    {
        $builder = SerializerBuilder::create();
        $builder->setPropertyNamingStrategy(new LowercaseNamingStrategy());

        $this->serializer = $builder->build();
        $this->validate = new Validate();
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

        if (is_int($value) || is_float($value)) {
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

    public function assertXmlIsValid($xml, $dtdPath)
    {
        $this->assertTrue($this->validate->isValid($xml, $dtdPath));
    }
}
