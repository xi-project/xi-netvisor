<?php

namespace Xi\Netvisor\Resource;

use Xi\Netvisor\Xml\TestMaterial;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Xi\Netvisor\XmlTestCase;

class MaterialTest extends XmlTestCase
{
    /**
     * @var TestMaterial
     */
    private $material;

    public function setUp()
    {
        parent::setUp();

        $material = new TestMaterial();
        $material->attribute = 'attr';
        $material->value = 'lus';

        $this->material = $material;
    }

    /**
     * @test
     */
    public function convertsToXml()
    {
        $xml = $this->toXml($this->material);

        $this->assertStringStartsWith('<?xml', $xml);
    }
}