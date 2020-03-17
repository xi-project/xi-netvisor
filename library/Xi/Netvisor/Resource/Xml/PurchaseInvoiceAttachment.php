<?php

namespace Xi\Netvisor\Resource\Xml;

class PurchaseInvoiceAttachment
{
    private $mimetype = 'application/pdf';
    private $attachmentdescription;
    private $filename;
    private $documentdata;

    public function __construct($description, $filename, $documentdata)
    {
        $this->attachmentdescription = $description;
        $this->filename = $filename;
        $this->documentdata = base64_encode($documentdata);
    }
}
