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

        $this->assertXmlContainsTagWithValue('productidentifier', '100', $xml);
        $this->assertXmlContainsTagWithAttributes('productidentifier', array('type' => 'netvisor'), $xml);

        $this->assertXmlContainsTagWithValue('productname', 'Product name, which is longer than the limit of 50', $xml);
        $this->assertNotContains('Product name, which is longer than the limit of 50 characters', $xml);

        $this->assertXmlContainsTagWithValue('productunitprice', '1,23', $xml);
        $this->assertXmlContainsTagWithAttributes('productunitprice', array('type' => 'net'), $xml);

        $this->assertXmlContainsTagWithValue('productvatpercentage', '24', $xml);
        $this->assertXmlContainsTagWithAttributes('productvatpercentage', array('vatcode' => 'KOMY'), $xml);

        $this->assertXmlContainsTagWithValue('salesinvoiceproductlinequantity', '5', $xml);
    }

    /**
     * @test
     */
    public function xmlHasAddedDimensionLines()
    {
        $name = 'Test dimension name';
        $item = 'Test dimension item';
        $name2 = 'Another test dimension name';
        $item2 = 'Another test dimension item';

        $this->invoiceProductLine->addDimension($name, $item);
        $this->invoiceProductLine->addDimension($name2, $item2);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertSame(2, substr_count($xml, '<dimensionname>'));
        $this->assertContains($name, $xml);
        $this->assertContains($item, $xml);
        $this->assertContains($name2, $xml);
        $this->assertContains($item, $xml);
    }

    /**
     * @dataProvider unitPriceTypeProvider
     */
    public function testSetUnitPriceType($type)
    {
        $this->invoiceProductLine->setUnitPriceType($type);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithAttributes('productunitprice', array('type' => $type), $xml);
    }

    public function unitPriceTypeProvider()
    {
        return [
            [SalesInvoiceProductLine::UNIT_PRICE_TYPE_WITH_VAT],
            [SalesInvoiceProductLine::UNIT_PRICE_TYPE_WITHOUT_VAT],
        ];
    }

    public function testSetFreeText()
    {
        $text = 'Additional information';
        $this->invoiceProductLine->setFreeTezt($text);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('salesinvoiceproductlinefreetext', $text, $xml);
    }
}
