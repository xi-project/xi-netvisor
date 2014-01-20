<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlValue;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @XmlRoot("SalesInvoice")
 * @ExclusionPolicy("none")
 */
class SalesInvoice extends Root // TODO: This has to be inside a Root tag.
{
    private $salesInvoiceDate;
    private $salesInvoiceAmount;
    private $salesInvoiceStatus = 'Open';

    /**
     * @param \DateTime $date
     */
    public function setSalesInvoiceDate(\DateTime $date)
    {
        $this->salesInvoiceDate = $date->format('Y-m-d');
    }

    /**
     * Use comma as decimal separator.
     *
     * @param string $amount
     */
    public function setSalesInvoiceAmount($amount)
    {
        $this->salesInvoiceAmount = $amount;
    }

    /**
     * @param string $status
     */
    public function setSalesInvoiceStatus($status)
    {
        $this->salesInvoiceStatus = $status;
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('salesinvoice.dtd');
    }
}
