<?php

namespace Xi\Netvisor\Component;

use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Resource\Xml\TestRoot;
use Xi\Netvisor\XmlTestCase;

class ValidateTest extends XmlTestCase
{
    /**
     * @var Validate
     */
    private $validate;

    public function setUp()
    {
        parent::setUp();

        $this->validate = new Validate();
    }

    /**
     * @test
     */
    public function validatesXmlAgainstDtd()
    {
        $root = new TestRoot();
        $root->setValue('value');

        $this->assertTrue(
            $this->validate->isValid($this->toXml($root), $root->getDtdPath())
        );
    }

    /**
     * @test
     */
    public function isNotValidIfXmlDoesNotSatisfyDtd()
    {
        $root = new TestRoot();
        $root->setValue('value');

        $this->assertTrue(
            $this->validate->isValid($this->toXml($root), $root->getDtdPath())
        );
    }
}
