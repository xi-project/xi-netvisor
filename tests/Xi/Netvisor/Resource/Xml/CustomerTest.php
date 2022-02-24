<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Customer;
use Xi\Netvisor\Resource\Xml\CustomerBaseInformation;
use Xi\Netvisor\XmlTestCase;

class CustomerTest extends XmlTestCase
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var CustomerBaseInformation
     */
    private $baseInformation;

    public function setUp(): void
    {
        parent::setUp();

        $this->baseInformation = new CustomerBaseInformation(
            'Testi Oy',
            'Testikatu 1',
            'Helsinki',
            '00240',
            'FI'
        );

        $this->customer = new Customer($this->baseInformation, null);
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

        $this->assertXmlContainsTagWithValue('name', 'Testi Oy', $xml);
        $this->assertXmlIsValid($xml, $this->customer->getDtdPath());
    }

    public function testSetPhoneNumber()
    {
        $number = '0501234567';
        $this->baseInformation->setPhoneNumber($number);
        $xml = $this->toXml($this->customer->getSerializableObject());
        $this->assertXmlContainsTagWithValue('phonenumber', $number, $xml);
    }

    public function testSetEmail()
    {
        $email = 'asdf@asdf.fi';
        $this->baseInformation->setEmail($email);
        $xml = $this->toXml($this->customer->getSerializableObject());
        $this->assertXmlContainsTagWithValue('email', $email, $xml);
    }

    /**
     * @dataProvider businessIdProvider
     */
    public function testSetBusinessId($id)
    {
        if (!is_null($id)) {
            $this->baseInformation->setBusinessId($id);
        }

        $xml = $this->toXml($this->customer->getSerializableObject());

        if (!$id) {
            $this->assertXmlDoesNotContainTag('externalidentifier', $xml);
            $this->assertXmlContainsTagWithValue('isprivatecustomer', 1, $xml);
            return;
        }

        $this->assertXmlContainsTagWithValue('externalidentifier', $id, $xml);
        $this->assertXmlContainsTagWithValue('isprivatecustomer', 0, $xml);
    }

    public function businessIdProvider()
    {
        return [
            ['9-876543'],
            [''],
            [null],
        ];
    }
}
