<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class PurchaseInvoiceLine
{
    private $productname;
    private $deliveredamount;
    private $unitprice;
    private $vatpercent;
    private $linesum;
    private $accountingsuggestion;

    /**
     * @XmlList(inline = true, entry = "dimension")
     */
    private $dimensions = [];

    /**
     * @param string $productName
     * @param float $deliveredAmount
     * @param float $unitPrice
     * @param int $vatPercent
     * @param float $lineSum
     */
    public function __construct(
        $productName,
        $deliveredAmount,
        $unitPrice,
        $vatPercent,
        $lineSum
    ) {
        $this->productname = substr($productName, 0, 200);
        $this->deliveredamount = $deliveredAmount;
        $this->unitprice = $unitPrice;
        $this->vatpercent = $vatPercent;

        $this->linesum = new AttributeElement(
            round($lineSum, 2), array('type' => 'brutto')
        );
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
     * @param int $account
     * @return self
     */
    public function setAccountingAccount($account)
    {
        $this->accountingsuggestion = $account;
        return $this;
    }
}
