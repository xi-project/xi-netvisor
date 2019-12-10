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
    private $paymentTermNetDays;
    private $deliveryaddressname;
    private $deliveryaddressline;
    private $deliveryaddresspostnumber;
    private $deliveryaddresstown;
    private $deliveryaddresscountrycode;
    private $salesinvoicenumber;
    private $salesinvoicereferencenumber;
    private $salesinvoicefreetextafterlines;

    /**
     * @XmlList(entry = "invoiceline")
     */
    private $invoiceLines = array();

    /**
     * @param \DateTime $salesInvoiceDate
     * @param string    $salesInvoiceAmount
     * @param string    $salesInvoiceStatus
     * @param string    $invoicingCustomerIdentifier
     * @param int       $paymentTermNetDays
     */
    public function __construct(
        \DateTime $salesInvoiceDate,
        $salesInvoiceAmount,
        $salesInvoiceStatus,
        $invoicingCustomerIdentifier,
        $paymentTermNetDays
    ) {
        parent::__construct();

        $this->salesInvoiceDate = $salesInvoiceDate->format('Y-m-d');
        $this->salesInvoiceAmount = $salesInvoiceAmount;
        $this->salesInvoiceStatus = new AttributeElement($salesInvoiceStatus, array('type' => 'netvisor'));
        $this->invoicingCustomerIdentifier = new AttributeElement($invoicingCustomerIdentifier, array('type' => 'netvisor')); // TODO: Type can be netvisor/customer.
        $this->paymentTermNetDays = $paymentTermNetDays;
    }

    /**
     * @param SalesInvoiceProductLine $line
     * @return self
     */
    public function addSalesInvoiceProductLine(SalesInvoiceProductLine $line)
    {
        $this->invoiceLines[] = new WrapperElement('salesinvoiceproductline', $line);
        return $this;
    }

    /**
     * @param string $receiverName
     * @param string $streetAddress
     * @param string $postNumber
     * @param string $town
     * @param string $countryCode
     * @return self
     */
    public function setDeliveryReceiverDetails(
        $receiverName,
        $streetAddress,
        $postNumber,
        $town,
        $countryCode
    ) {
        $map = [
            'deliveryaddressname' => $receiverName,
            'deliveryaddressline' => $streetAddress,
            'deliveryaddresspostnumber' => $postNumber,
            'deliveryaddresstown' => $town,
            'deliveryaddresscountrycode' => $countryCode,
        ];

        foreach ($map as $xmlField => $value) {
            if (!$value) {
                $this->$xmlField = null;
                continue;
            }

            $attributes = array();

            if ($xmlField === 'deliveryaddresscountrycode') {
                $attributes = array('type' => 'ISO-3316');
            }

            $this->$xmlField = new AttributeElement($value, $attributes);
        }

        return $this;
    }

    /**
     * @param string $invoiceNumber
     * @return self
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->salesinvoicenumber = $invoiceNumber;
        return $this;
    }

    /**
     * @param string $referenceNumber
     * @return self
     */
    public function setReferenceNumber($referenceNumber)
    {
        $this->salesinvoicereferencenumber = $referenceNumber;
        return $this;
    }

    /**
     * @param string $text
     * @return self
     */
    public function setAfterLinesText($text)
    {
        $this->salesinvoicefreetextafterlines = $text;
        return $this;
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
