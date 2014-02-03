<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\TestResource;
use Xi\Netvisor\XmlTestCase;

class TestResourceTest extends XmlTestCase
{
    /**
     * @test
     */
    public function convertsToXml()
    {
        $resource = new TestResource();
        $resource->setValue('value');

        $this->assertStringStartsWith('<?xml', $this->toXml($resource->getSerializableObject()));
    }
}
