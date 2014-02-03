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
            '616'
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

        $this->assertXmlContainsTagWithValue('salesInvoiceDate', '2014-01-20', $xml);
        $this->assertXmlContainsTagWithValue('salesInvoiceAmount', '5,00', $xml);

        $this->assertXmlContainsTagWithValue('salesInvoiceStatus', 'Open', $xml);
        $this->assertXmlContainsTagWithAttributes('salesInvoiceStatus', array('type' => 'netvisor'), $xml);

        $this->assertXmlContainsTagWithValue('invoicingCustomerIdentifier', '616', $xml);
        $this->assertXmlContainsTagWithAttributes('invoicingCustomerIdentifier', array('type' => 'netvisor'), $xml);
    }

    /**
     * @test
     */
    public function xmlHasAddedSalesInvoiceProductLines()
    {
        $this->invoice->addSalesInvoiceProductLine(new SalesInvoiceProductLine('1', 'A', '1,00', '24', '1'));
        $this->invoice->addSalesInvoiceProductLine(new SalesInvoiceProductLine('2', 'B', '1,00', '24', '1'));

        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertContains('invoiceLines', $xml);
        $this->assertContains('invoiceLine', $xml);
        $this->assertContains('salesInvoiceProductLine', $xml);

        $this->assertXmlContainsTagWithValue('productIdentifier', '1', $xml);
        $this->assertXmlContainsTagWithValue('productIdentifier', '2', $xml);
    }
}
