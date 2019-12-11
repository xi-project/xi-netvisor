<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class SalesInvoiceProductLine
{
    const PRODUCT_IDENTIFIER_TYPE_CUSTOMER = 'customer';
    const PRODUCT_IDENTIFIER_TYPE_NETVISOR = 'netvisor';
    const UNIT_PRICE_TYPE_WITH_VAT = 'gross';
    const UNIT_PRICE_TYPE_WITHOUT_VAT = 'net';

    private $productIdentifier;
    private $productName;
    private $productUnitPrice;
    private $productVatPercentage;
    private $salesInvoiceProductLineQuantity;
    private $salesinvoiceproductlinefreetext;

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
        $this->productIdentifier = new AttributeElement(
            $productIdentifier,
            array('type' => self::PRODUCT_IDENTIFIER_TYPE_NETVISOR)
        );

        $this->productName = substr($productName, 0, 50);

        $this->productUnitPrice = new AttributeElement(
            $productUnitPrice, array('type' => self::UNIT_PRICE_TYPE_WITHOUT_VAT)
        );
        
        $this->productVatPercentage = new AttributeElement($productVatPercentage, array('vatcode' => 'KOMY')); // TODO: different values.
        $this->salesInvoiceProductLineQuantity = $salesInvoiceProductLineQuantity;
    }

    /**
     * @param string $name
     * @param string $item
     * @return self
     */
    public function addDimension($name, $item)
    {
        $this->dimensions[] = new Dimension($name, $item);
        return $this;
    }

    /**
     * @param string $name
     * @param string $item
     * @return self
     */
    public function setProductIdentiefierType($type)
    {
        $this->productIdentifier->setAttribute('type', $type);
        return $this;
    }

    /**
     * @param string $name
     * @param string $item
     * @return self
     */
    public function setUnitPriceType($type)
    {
        $this->productUnitPrice->setAttribute('type', $type);
        return $this;
    }

    /**
     * @param string $text
     * @return self
     */
    public function setFreeText($text)
    {
        $this->salesinvoiceproductlinefreetext = $text;
        return $this;
    }
}
