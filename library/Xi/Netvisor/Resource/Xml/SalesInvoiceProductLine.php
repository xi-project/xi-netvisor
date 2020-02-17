<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class SalesInvoiceProductLine
{
    public const PRODUCT_IDENTIFIER_TYPE_CUSTOMER = 'customer';
    public const PRODUCT_IDENTIFIER_TYPE_NETVISOR = 'netvisor';
    public const UNIT_PRICE_TYPE_WITH_VAT = 'gross';
    public const UNIT_PRICE_TYPE_WITHOUT_VAT = 'net';
    public const VAT_CODE_KOMY = 'KOMY';
    public const VAT_CODE_NONE = 'NONE';

    private $productIdentifier;
    private $productName;
    private $productUnitPrice;
    private $productVatPercentage;
    private $salesInvoiceProductLineQuantity;
    private $salesInvoiceProductLineDiscountPercentage;
    private $salesinvoiceproductlinefreetext;
    private $accountingaccountsuggestion;

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

        $this->productName = substr($productName, 0, 200);

        $this->productUnitPrice = new AttributeElement(
            $productUnitPrice, array('type' => self::UNIT_PRICE_TYPE_WITHOUT_VAT)
        );
        
        $this->productVatPercentage = new AttributeElement(
            $productVatPercentage, array('vatcode' => static::VAT_CODE_KOMY)
        );

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
     * @param string $type
     * @return self
     */
    public function setProductIdentiefierType($type)
    {
        $this->productIdentifier->setAttribute('type', $type);
        return $this;
    }

    /**
     * @param string $type
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

    /**
     * @param int $account
     * @return self
     */
    public function setAccountingAccount($account)
    {
        $this->accountingaccountsuggestion = $account;
        return $this;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setVatCode($code)
    {
        $this->productVatPercentage->setAttribute('vatcode', $code);
        return $this;
    }

    /**
     * @param float $discountPercentage
     * @return self
     */
    public function setDiscountPercentage($discountPercentage)
    {
        $this->salesInvoiceProductLineDiscountPercentage = $discountPercentage;
        return $this;
    }
}
