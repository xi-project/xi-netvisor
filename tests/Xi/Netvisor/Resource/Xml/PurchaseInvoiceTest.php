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

    public function setUp(): void
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

        $this->assertXmlIsValid($xml, $this->invoice->getDtdPath());
    }

    public function testAddAttachment()
    {
        $this->invoice->addAttachment(
            new PurchaseInvoiceAttachment('Desc 1', 'File 1', 'Data 1')
        );

        $this->invoice->addAttachment(
            new PurchaseInvoiceAttachment('Desc 2', 'File 2', 'Data 2')
        );

        $xml = $this->toXml($this->invoice->getSerializableObject());

        $this->assertContains('purchaseinvoiceattachments', $xml);
        $this->assertContains('purchaseinvoiceattachment', $xml);

        $this->assertXmlContainsTagWithValue('attachmentdescription', 'Desc 1', $xml);
        $this->assertXmlContainsTagWithValue('attachmentdescription', 'Desc 2', $xml);

        $this->assertXmlIsValid($xml, $this->invoice->getDtdPath());
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

    /**
     * @dataProvider setVendorDetailsProvider
     */
    public function testSetVendorDetails(
        $bankAccount,
        $businessId,
        $name,
        $streetAddress,
        $postNumber,
        $city,
        $countryCode,
        $phone,
        $email
    ) {
        $this->invoice->setVendorDetails(
            $bankAccount,
            $businessId,
            $name,
            $streetAddress,
            $postNumber,
            $city,
            $countryCode,
            $phone,
            $email
        );

        $xml = $this->toXml($this->invoice->getSerializableObject());

        $map = [
            'accountnumber' => [
                'value' => $bankAccount,
                'maxlength' => null,
            ],
            'organizationidentifier' => [
                'value' => $businessId,
                'maxlength' => null,
            ],
            'vendorname' => [
                'value' => $name,
                'maxlength' => 250,
            ],
            'vendoraddressline' => [
                'value' => $streetAddress,
                'maxlength' => 80,
            ],
            'vendorpostnumber' => [
                'value' => $postNumber,
                'maxlength' => 50,
            ],
            'vendorcity' => [
                'value' => $city,
                'maxlength' => 50,
            ],
            'vendorcountry' => [
                'value' => $countryCode,
                'maxlength' => 2,
            ],
            'vendorphonenumber' => [
                'value' => $phone,
                'maxlength' => 80,
            ],
            'vendoremail' => [
                'value' => $email,
                'maxlength' => 80,
            ],
        ];

        foreach ($map as $key => $data) {
            if (!$data['value']) {
                $this->assertXmlDoesNotContainTag($key, $xml);
                continue;
            }

            $value = !is_null($data['maxlength']) ?
                substr($data['value'], 0, $data['maxlength']) :
                $data['value'];

            $this->assertXmlContainsTagWithValue($key, $value, $xml);
        }
    }

    public function setVendorDetailsProvider()
    {
        return [
            [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ],
            [
                '12315456',
                '448414-4',
                'Vendor name',
                'Address 13',
                '012345',
                'Vendor city',
                'FI',
                '0501234567',
                'vendor@email.com',
            ],
            [
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
                str_repeat('1', 300),
            ],
        ];
    }

    public function testSetBankReferenceNumber()
    {
        $reference = '0123154891315';
    
        $this->invoice->setBankReferenceNumber($reference);
        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('bankreferencenumber', $reference, $xml);
    }

    /**
     * @dataProvider setInvoiceSourceProvider
     */
    public function testSetInvoiceSource($source, $expectException)
    {
        if ($expectException) {
            $this->expectException(\Exception::class);
        }

        $this->invoice->setInvoiceSource($source);

        $xml = $this->toXml($this->invoice->getSerializableObject());
        $this->assertXmlContainsTagWithValue('invoicesource', $source, $xml);
    }

    public function setInvoiceSourceProvider()
    {
        return [
            [PurchaseInvoice::INVOICE_SOURCE_FINVOICE, false],
            [PurchaseInvoice::INVOICE_SOURCE_MANUAL, false],
            ['Something else', true],
        ];
    }
}
