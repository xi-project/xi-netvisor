<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\TestRoot;
use Xi\Netvisor\XmlTestCase;

class TestRootTest extends XmlTestCase
{
    /**
     * @test
     */
    public function convertsToXml()
    {
        $root = new TestRoot();
        $root->setValue('value');

        $this->assertStringStartsWith('<?xml', $this->toXml($root));
    }
}