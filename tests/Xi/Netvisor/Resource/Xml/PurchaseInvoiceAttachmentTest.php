<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\XmlTestCase;

class PurchaseInvoiceAttachmentTest extends XmlTestCase
{
    /**
     * @var PurchaseInvoiceAttachment
     */
    private $attachment;

    public function setUp(): void
    {
        parent::setUp();

        $this->attachment = new PurchaseInvoiceAttachment(
            'application/pdf',
            'PDF',
            'filename.pdf',
            'data'
        );
    }

    /**
     * @test
     */
    public function xmlHasRequiredSalesInvoiceValues()
    {
        $description = 'description';
        $filename = 'filename.pdf';
        $data = 'data';

        $attachment = new PurchaseInvoiceAttachment(
            $description,
            $filename,
            $data
        );

        $xml = $this->toXml($attachment);

        $this->assertXmlContainsTagWithValue('mimetype', 'application/pdf', $xml);
        $this->assertXmlContainsTagWithValue('attachmentdescription', $description, $xml);
        $this->assertXmlContainsTagWithValue('filename', $filename, $xml);
        $this->assertXmlContainsTagWithValue('documentdata', base64_encode($data), $xml);
    }
}
