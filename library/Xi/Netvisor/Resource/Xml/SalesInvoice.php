<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlValue;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Inline;

/**
 * @XmlRoot("SalesInvoice")
 * @ExclusionPolicy("none")
 */
class SalesInvoice extends Root // TODO: This has to be inside a Root tag.
{
    private $salesInvoiceDate;
    private $salesInvoiceAmount;
    private $salesInvoiceStatus;
    private $invoicingCustomerIdentifier;

    public function __construct(
        \DateTime $salesInvoiceDate,
        $salesInvoiceAmount,
        $salesInvoiceStatus,
        $invoicingCustomerIdentifier
    ) {
        $this->salesInvoiceDate = $salesInvoiceDate->format('Y-m-d');
        $this->salesInvoiceAmount = $salesInvoiceAmount;
        $this->salesInvoiceStatus = new Element($salesInvoiceStatus, ['type' => 'netvisor']);
        $this->invoicingCustomerIdentifier = $invoicingCustomerIdentifier;
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('salesinvoice.dtd');
    }
}
