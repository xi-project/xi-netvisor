<?php

namespace Xi\Netvisor\Component;

use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Resource\Xml\TestResource;
use Xi\Netvisor\XmlTestCase;

class ValidateTest extends XmlTestCase
{
    /**
     * @var Validate
     */
    private $validate;

    public function setUp(): void
    {
        parent::setUp();

        $this->validate = new Validate();
    }

    /**
     * @test
     */
    public function validatesXmlAgainstDtd()
    {
        $resource = new TestResource();
        $resource->setValue('value');

        $this->assertTrue(
            $this->validate->isValid($this->toXml($resource->getSerializableObject()), $resource->getDtdPath())
        );
    }

    /**
     * @test
     */
    public function isNotValidIfXmlDoesNotSatisfyDtd()
    {
        $resource = new TestResource();
        $resource->setValue('value');

        $this->assertTrue(
            $this->validate->isValid($this->toXml($resource->getSerializableObject()), $resource->getDtdPath())
        );
    }
}
