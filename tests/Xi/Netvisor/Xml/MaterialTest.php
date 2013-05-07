<?php

namespace Xi\Netvisor\Resource;

use Xi\Netvisor\Xml\TestMaterial;
use Xi\Netvisor\XmlTestCase;

class MaterialTest extends XmlTestCase
{
    /**
     * @test
     */
    public function convertsToXml()
    {
        $material = new TestMaterial();
        $material->attribute = 'attr';
        $material->value = 'lus';

        $xml = $this->toXml($material);

        $this->assertStringStartsWith('<?xml', $xml);
    }
}