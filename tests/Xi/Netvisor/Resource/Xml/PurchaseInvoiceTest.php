<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\PurchaseInvoice;
use Xi\Netvisor\XmlTestCase;

class PurchaseInvoiceTest extends XmlTestCase
{
    /**
     * @var PurchaseInvoice
     */
    private $invoice;

    public function setUp()
    {
        parent::setUp();

        $this->invoice = new PurchaseInvoice(
            123,
            new \DateTime(),
            new \DateTime(),
            new \DateTime(),
            321
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
        $invoiceNumber = 123;
        $invoiceDate = new \DateTime('2000-01-01');
        $valueDate = new \DateTime('2001-01-01');
        $dueDate = new \DateTime('2001-01-01');
        $amount = 321.278;

        $invoice = new PurchaseInvoice(
            $invoiceNumber,
            $invoiceDate,
            $valueDate,
            $dueDate,
            $amount
        );

        $xml = $this->toXml($invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('invoicenumber', $invoiceNumber, $xml);

        $this->assertXmlContainsTagWithValue('invoicedate', $invoiceDate->format('Y-m-d'), $xml);
        $this->assertXmlContainsTagWithAttributes('invoicedate', array('format' => 'ansi'), $xml);

        $this->assertXmlContainsTagWithValue('valuedate', $valueDate->format('Y-m-d'), $xml);
        $this->assertXmlContainsTagWithAttributes('valuedate', array('format' => 'ansi'), $xml);

        $this->assertXmlContainsTagWithValue('duedate', $dueDate->format('Y-m-d'), $xml);
        $this->assertXmlContainsTagWithAttributes('duedate', array('format' => 'ansi'), $xml);

        $this->assertXmlContainsTagWithValue('amount', round($amount, 2), $xml);
        $this->assertNotContains((string) $amount, $xml);
    }

    /**
     * @test
     */
    public function xmlHasAddedPurchaseInvoiceLines()
    {
        $this->invoice->addPurchaseInvoiceLine(
            new PurchaseInvoiceLine('Name 1', 1, 1, 24, 1.24)
        );

        $this->invoice->addPurchaseInvoiceLine(
            new PurchaseInvoiceLine('Name 2', 2, 2, 24, 4.96)
        );

        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertContains('purchaseinvoicelines', $xml);
        $this->assertContains('purchaseinvoiceline', $xml);

        $this->assertXmlContainsTagWithValue('productname', 'Name 1', $xml);
        $this->assertXmlContainsTagWithValue('productname', 'Name 2', $xml);
    }

    public function testSetComment()
    {
        $comment = 'Some additional data';
    
        $this->invoice->setComment($comment);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('comment', $comment, $xml);

        // Too long
        while (strlen($comment) <= 255) {
            $comment .= $comment;
        }

        $this->invoice->setComment($comment);
        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('comment', substr($comment, 0, 255), $xml);
        $this->assertNotContains($comment, $xml);
    }
}
