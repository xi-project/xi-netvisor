<?php

namespace Xi\Netvisor\Resource\Xml;

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
        $xml = $this->toXml($this->invoice);

        $this->assertXmlContainsTagWithValue('salesInvoiceDate', '2014-01-20', $xml);
        $this->assertXmlContainsTagWithValue('salesInvoiceAmount', '5,00', $xml);
        $this->assertXmlContainsTagWithValue('salesInvoiceStatus', 'Open', $xml);
        $this->assertXmlContainsTagWithAttributes('salesInvoiceStatus', ['type' => 'netvisor'], $xml);
        $this->assertXmlContainsTagWithValue('invoicingCustomerIdentifier', '616', $xml);

        var_dump($xml);
    }
}

