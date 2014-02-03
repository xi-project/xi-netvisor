<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\SalesInvoiceProductLine;
use Xi\Netvisor\XmlTestCase;

class SalesInvoiceProductLineTest extends XmlTestCase
{
    /**
     * @var SalesInvoiceProductLine
     */
    private $invoiceProductLine;

    public function setUp()
    {
        parent::setUp();

        $this->invoiceProductLine = new SalesInvoiceProductLine(
            '100',
            'Product name, which is longer than the limit of 50 characters',
            '1,23',
            '24',
            '5'
        );
    }

    /**
     * @test
     */
    public function xmlHasRequiredProductLineValues()
    {
        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('productIdentifier', '100', $xml);
        $this->assertXmlContainsTagWithAttributes('productIdentifier', array('type' => 'netvisor'), $xml);

        $this->assertXmlContainsTagWithValue('productName', 'Product name, which is longer than the limit of 50', $xml);
        $this->assertNotContains('Product name, which is longer than the limit of 50 characters', $xml);

        $this->assertXmlContainsTagWithValue('productUnitPrice', '1,23', $xml);
        $this->assertXmlContainsTagWithAttributes('productUnitPrice', array('type' => 'net'), $xml);

        $this->assertXmlContainsTagWithValue('productVatPercentage', '24', $xml);
        $this->assertXmlContainsTagWithAttributes('productVatPercentage', array('VatCode' => 'KOMY'), $xml);

        $this->assertXmlContainsTagWithValue('salesInvoiceProductLineQuantity', 5, $xml);
    }
}
