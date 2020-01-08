<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class VoucherLine
{
    public const UNIT_PRICE_TYPE_WITH_VAT = 'gross';
    public const UNIT_PRICE_TYPE_WITHOUT_VAT = 'net';
    public const VAT_CODE_KOMY = 'KOMY';
    public const VAT_CODE_NONE = 'NONE';

    private $lineSum;
    private $accountNumber;
    private $vatPercent;

    /**
     * @XmlList(inline = true, entry = "dimension")
     */
    private $dimensions = [];

    public function __construct($lineSum, $accountNumber, $vatPercent)
    {
        $this->lineSum = new AttributeElement($lineSum, array('type' => self::UNIT_PRICE_TYPE_WITHOUT_VAT));
        $this->accountNumber = $accountNumber;
        $this->vatPercent = new AttributeElement($vatPercent, array('vatcode' => static::VAT_CODE_KOMY));
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
    public function setLineSumType($type)
    {
        $this->lineSum->setAttribute('type', $type);
        return $this;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setVatCode($code)
    {
        $this->vatPercent->setAttribute('vatcode', $code);
        return $this;
    }
}
