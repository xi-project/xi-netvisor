<?php

namespace Xi\Netvisor\Resource;

use Xi\Netvisor\Xml\TestRoot;
use Xi\Netvisor\XmlTestCase;

class XmlTest extends XmlTestCase
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