<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\Element;

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

    /**
     * @param \DateTime $salesInvoiceDate
     * @param string    $salesInvoiceAmount
     * @param string    $salesInvoiceStatus
     * @param string    $invoicingCustomerIdentifier
     */
    public function __construct(
        \DateTime $salesInvoiceDate,
        $salesInvoiceAmount,
        $salesInvoiceStatus,
        $invoicingCustomerIdentifier
    ) {
        $this->salesInvoiceDate = $salesInvoiceDate->format('Y-m-d');
        $this->salesInvoiceAmount = $salesInvoiceAmount;
        $this->salesInvoiceStatus = new Element($salesInvoiceStatus, ['type' => 'netvisor']);
        $this->invoicingCustomerIdentifier = new Element($invoicingCustomerIdentifier, ['type' => 'netvisor']); // TODO: Type can be netvisor/customer.
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('salesinvoice.dtd');
    }
}
