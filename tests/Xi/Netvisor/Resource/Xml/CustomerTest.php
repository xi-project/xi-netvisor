<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;
use Xi\Netvisor\Resource\Xml\Customer;
use Xi\Netvisor\XmlTestCase;

class CustomerTest extends XmlTestCase
{
    /**
     * @var Customer
     */
    private $customer;

    public function setUp()
    {
        parent::setUp();

        $this->customer = new Customer(
            new CustomerBaseInformation(
                '1234567-1',
                'Testi Oy',
                'Testikatu 1',
                'Helsinki',
                '00240',
                'FI'
            ),
            null
        );
    }

    /**
     * @test
     */
    public function hasDtd()
    {
        $this->assertNotNull($this->customer->getDtdPath());
    }

    /**
     * @test
     */
    public function xmlHasRequiredValues()
    {
        $xml = $this->toXml($this->customer->getSerializableObject());

        $this->assertXmlContainsTagWithValue('externalidentifier', '1234567-1', $xml);
        $this->assertXmlContainsTagWithValue('name', 'Testi Oy', $xml);
    }
}
