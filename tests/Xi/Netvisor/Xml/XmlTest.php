<?php

namespace Xi\Netvisor\Resource;

use Xi\Netvisor\Xml\TestXml;
use Xi\Netvisor\XmlTestCase;

class XmlTest extends XmlTestCase
{
    /**
     * @test
     */
    public function convertsToXml()
    {
        $material = new TestXml();
        $material->attribute = 'attribute';
        $material->value = 'value';

        $this->assertStringStartsWith('<?xml', $this->toXml($material));
    }
}