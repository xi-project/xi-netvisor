<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\Element;

/**
 * @XmlRoot("SalesInvoiceProductLine")
 * @ExclusionPolicy("none")
 */
class SalesInvoiceProductLine
{
    private $productIdentifier;
    private $productName;
    private $productUnitPrice;
    private $productVatPercentage;
    private $salesInvoiceProductLineQuantity;

    public function __construct(
        $productIdentifier,
        $productName,
        $productUnitPrice,
        $productVatPercentage,
        $salesInvoiceProductLineQuantity
    ) {
        $this->productIdentifier = new Element($productIdentifier, ['type' => 'netvisor']); // TODO: netvisor/customer.
        $this->productName = substr($productName, 0, 50);
        $this->productUnitPrice = new Element($productUnitPrice, ['type' => 'net']); // TODO: net/gross.
        $this->productVatPercentage = new Element($productVatPercentage, ['VatCode' => 'KOMY']); // TODO: different values.
        $this->salesInvoiceProductLineQuantity = $salesInvoiceProductLineQuantity;
    }
}
