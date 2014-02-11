<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;

/**
 * TODO: Should be kept immutable?
 */
class SalesInvoice extends Root
{
    private $salesInvoiceDate;
    private $salesInvoiceAmount;
    private $salesInvoiceStatus;
    private $invoicingCustomerIdentifier;

    /**
     * @XmlList(entry = "invoiceline")
     */
    private $invoiceLines = array();

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
        $this->salesInvoiceStatus = new AttributeElement($salesInvoiceStatus, array('type' => 'netvisor'));
        $this->invoicingCustomerIdentifier = new AttributeElement($invoicingCustomerIdentifier, array('type' => 'netvisor')); // TODO: Type can be netvisor/customer.
    }

    /**
     * @param SalesInvoiceProductLine $line
     */
    public function addSalesInvoiceProductLine(SalesInvoiceProductLine $line)
    {
        $this->invoiceLines[] = new WrapperElement('salesInvoiceProductLine', $line);
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('salesinvoice.dtd');
    }

    protected function getXmlName()
    {
        return 'salesinvoice';
    }
}
