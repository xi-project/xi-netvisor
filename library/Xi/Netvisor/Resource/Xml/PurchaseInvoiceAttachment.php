<?php

namespace Xi\Netvisor\Resource\Xml;

class PurchaseInvoiceAttachment
{
    private $mimetype;
    private $attachmentdescription;
    private $filename;
    private $documentdata;

    public function __construct($mimetype, $description, $filename, $documentdata)
    {
        $this->mimetype = $mimetype;
        $this->attachmentdescription = $description;
        $this->filename = $filename;
        $this->documentdata = base64_encode($documentdata);
    }
}
