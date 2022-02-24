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

    public function setUp(): void
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

        $this->assertXmlIsValid($xml, $this->invoice->getDtdPath());
    }

    public function testSetDeliveryReceiverDetails()
    {
        $receiverName = 'Receiver';
        $streetAddress = 'Street address';
        $postNumber = '12345';
        $town = 'Town';
        $countryCode = 'FI';
    
        // With all values
        $this->invoice->setDeliveryReceiverDetails(
            $receiverName,
            $streetAddress,
            $postNumber,
            $town,
            $countryCode
        );
        
        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('deliveryaddressname', $receiverName, $xml);
        $this->assertXmlContainsTagWithValue('deliveryaddressline', $streetAddress, $xml);
        $this->assertXmlContainsTagWithValue('deliveryaddresspostnumber', $postNumber, $xml);
        $this->assertXmlContainsTagWithValue('deliveryaddresstown', $town, $xml);
        $this->assertXmlContainsTagWithValue('deliveryaddresscountrycode', $countryCode, $xml);

        $this->assertXmlContainsTagWithAttributes(
            'deliveryaddresscountrycode',
            array('type' => 'ISO-3316'),
            $xml
        );

        // Test with string, null or empty values
        $this->invoice->setDeliveryReceiverDetails($receiverName, null, '', null, '');
        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('deliveryaddressname', $receiverName, $xml);
        $this->assertNotContains('deliveryaddressline', $xml);
        $this->assertNotContains('deliveryaddresspostnumber', $xml);
        $this->assertNotContains('deliveryaddresstown', $xml);
        $this->assertNotContains('deliveryaddresscountrycode', $xml);
    }

    public function testSetInvoiceNumber()
    {
        $invoiceNumber = '0123456';
    
        $this->invoice->setInvoiceNumber($invoiceNumber);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('salesinvoicenumber', $invoiceNumber, $xml);
    }

    public function testSetReferenceNumber()
    {
        $referenceNumber = '0987654';
    
        $this->invoice->setReferenceNumber($referenceNumber);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        
        $this->assertXmlContainsTagWithValue(
            'salesinvoicereferencenumber',
            $referenceNumber,
            $xml
        );
    }

    public function testSetBeforeLinesText()
    {
        $text = 'Some additional data';
    
        $this->invoice->setBeforeLinesText($text);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('salesinvoicefreetextbeforelines', $text, $xml);

        // Too long
        while (strlen($text) <= 500) {
            $text .= $text;
        }

        $this->invoice->setBeforeLinesText($text);
        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('salesinvoicefreetextbeforelines', substr($text, 0, 500), $xml);
        $this->assertNotContains($text, $xml);
    }

    public function testSetAfterLinesText()
    {
        $text = 'Some additional data';
    
        $this->invoice->setAfterLinesText($text);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('salesinvoicefreetextafterlines', $text, $xml);

        // Too long
        while (strlen($text) <= 500) {
            $text .= $text;
        }

        $this->invoice->setAfterLinesText($text);
        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertXmlContainsTagWithValue('salesinvoicefreetextafterlines', substr($text, 0, 500), $xml);
        $this->assertNotContains($text, $xml);
    }

    public function testSetYourReference()
    {
        $text = 'Some reference data';
    
        $this->invoice->setYourReference($text);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('salesinvoiceyourreference', $text, $xml);
    }
}
