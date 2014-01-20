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

        $this->invoice = new SalesInvoice();

        $this->invoice->setSalesInvoiceDate(\DateTime::createFromFormat('Y-m-d', '2014-01-20'));
        $this->invoice->setSalesInvoiceAmount('5,00');
        $this->invoice->setSalesInvoiceStatus('Unsent');
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
    public function convertsToXml()
    {
        $xml = $this->toXml($this->invoice);

        $this->assertXmlContainsTagWithValue('salesInvoiceDate', '2014-01-20', $xml);
        $this->assertXmlContainsTagWithValue('salesInvoiceAmount', '5,00', $xml);
        $this->assertXmlContainsTagWithValue('salesInvoiceStatus', 'Unsent', $xml);

        var_dump($xml);
    }
}

