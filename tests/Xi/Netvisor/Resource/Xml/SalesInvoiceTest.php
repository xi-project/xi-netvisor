<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;
use Xi\Netvisor\Resource\Xml\SalesInvoice;
use Xi\Netvisor\XmlTestCase;

class SalesInvoiceTest extends XmlTestCase
{
    /**
     * @var SalesInvoice
     */
    private $invoice;

    public function setUp()
    {
        parent::setUp();

        $this->invoice = new SalesInvoice(
            \DateTime::createFromFormat('Y-m-d', '2014-01-20'),
            '5,00',
            'Open',
            '616',
            14
        );
    }

    /**
     * @test
     */
    public function hasDtd()
    {
        $this->assertNotNull($this->invoice->getDtdPath());
    }

    /**
     * @test
     */
    public function xmlHasRequiredSalesInvoiceValues()
    {
        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('salesinvoicedate', '2014-01-20', $xml);
        $this->assertXmlContainsTagWithValue('salesinvoiceamount', '5,00', $xml);

        $this->assertXmlContainsTagWithValue('salesinvoicestatus', 'Open', $xml);
        $this->assertXmlContainsTagWithAttributes('salesinvoicestatus', array('type' => 'netvisor'), $xml);

        $this->assertXmlContainsTagWithValue('invoicingcustomeridentifier', '616', $xml);
        $this->assertXmlContainsTagWithAttributes('invoicingcustomeridentifier', array('type' => 'netvisor'), $xml);
    }

    /**
     * @test
     */
    public function xmlHasAddedSalesInvoiceProductLines()
    {
        $this->invoice->addSalesInvoiceProductLine(new SalesInvoiceProductLine('1', 'A', '1,00', '24', '1'));
        $this->invoice->addSalesInvoiceProductLine(new SalesInvoiceProductLine('2', 'B', '1,00', '24', '1'));

        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertContains('invoicelines', $xml);
        $this->assertContains('invoiceline', $xml);
        $this->assertContains('salesinvoiceproductline', $xml);

        $this->assertXmlContainsTagWithValue('productidentifier', '1', $xml);
        $this->assertXmlContainsTagWithValue('productidentifier', '2', $xml);
    }

    /**
     * @test
     */
    public function xmlHasAddedSalesInvoiceProductDimensionLines()
    {
        $name = 'Test dimension name';
        $item = 'Test dimension item';
        $name2 = 'Another test dimension name';
        $item2 = 'Another test dimension item';

        $productLine = new SalesInvoiceProductLine('1', 'A', '1,00', '24', '1');
        $productLine->addDimension($name, $item);
        $productLine->addDimension($name2, $item2);
        
        $this->invoice->addSalesInvoiceProductLine($productLine);

        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertSame(2, substr_count($xml, '<dimensionname>'));
        $this->assertContains($name, $xml);
        $this->assertContains($item, $xml);
        $this->assertContains($name2, $xml);
        $this->assertContains($item, $xml);
    }
}
