<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class SalesInvoiceProductLine
{
    private $productIdentifier;
    private $productName;
    private $productUnitPrice;
    private $productVatPercentage;
    private $salesInvoiceProductLineQuantity;

    /**
     * @XmlList(inline = true, entry = "dimension")
     */
    private $dimensions = [];

    /**
     * @param string $productIdentifier
     * @param string $productName
     * @param string $productUnitPrice
     * @param string $productVatPercentage
     * @param int    $salesInvoiceProductLineQuantity
     */
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
        $this->productVatPercentage = new AttributeElement($productVatPercentage, array('vatcode' => 'KOMY')); // TODO: different values.
        $this->salesInvoiceProductLineQuantity = $salesInvoiceProductLineQuantity;
    }

    /**
     * @param string $name
     * @param string $item
     * @return self
     */
    public function addDimension($name, $item): self
    {
        $this->dimensions[] = new Dimension($name, $item);
        return $this;
    }
}
