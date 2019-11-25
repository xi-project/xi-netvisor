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
            '5',
            [
                'dimensions' => [
                    new Dimension('test name', 'test item 1'),
                    new Dimension('test name', 'test item 2'),
                ],
            ]
        );
    }

    /**
     * @test
     */
    public function xmlHasRequiredProductLineValues()
    {
        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('productidentifier', '100', $xml);
        $this->assertXmlContainsTagWithAttributes('productidentifier', array('type' => 'netvisor'), $xml);

        $this->assertXmlContainsTagWithValue('productname', 'Product name, which is longer than the limit of 50', $xml);
        $this->assertNotContains('Product name, which is longer than the limit of 50 characters', $xml);

        $this->assertXmlContainsTagWithValue('productunitprice', '1,23', $xml);
        $this->assertXmlContainsTagWithAttributes('productunitprice', array('type' => 'net'), $xml);

        $this->assertXmlContainsTagWithValue('productvatpercentage', '24', $xml);
        $this->assertXmlContainsTagWithAttributes('productvatpercentage', array('vatcode' => 'KOMY'), $xml);

        $this->assertXmlContainsTagWithValue('salesinvoiceproductlinequantity', 5, $xml);

        $this->assertXmlContainsTagWithValue('dimensionitem', 'test item 1', $xml);
    }
}
