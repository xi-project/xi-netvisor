<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

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
        $this->productIdentifier = new AttributeElement($productIdentifier, array('type' => 'netvisor')); // TODO: netvisor/customer.
        $this->productName = substr($productName, 0, 50);
        $this->productUnitPrice = new AttributeElement($productUnitPrice, array('type' => 'net')); // TODO: net/gross.
        $this->productVatPercentage = new AttributeElement($productVatPercentage, array('VatCode' => 'KOMY')); // TODO: different values.
        $this->salesInvoiceProductLineQuantity = $salesInvoiceProductLineQuantity;
    }
}
