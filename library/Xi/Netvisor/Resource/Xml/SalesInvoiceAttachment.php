<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class SalesInvoiceAttachment
{
    private $mimeType;
    private $attachmentDescription;
    private $fileName;
    private $documentData;
    private $printByDefault;

    /**
     * @param string $mimeType
     * @param string $attachmentDescription
     * @param string $fileName
     * @param string $documentData Binary string, will be base64 encoded.
     * @param string $documentDataType "finvoice" or "pdf"
     * @param int|null $printByDefault 0/1, Do not define if data type is "finvoice".
     */
    public function __construct(
        $mimeType,
        $attachmentDescription,
        $fileName,
        $documentData,
        $documentDataType,
        $printByDefault = null
    ) {
        $this->mimeType = $mimeType;
        $this->attachmentDescription = $attachmentDescription;
        $this->fileName = $fileName;
        $this->documentData = new AttributeElement(base64_encode($documentData), array('type' => $documentDataType));
        $this->printByDefault = $printByDefault;
    }
}
