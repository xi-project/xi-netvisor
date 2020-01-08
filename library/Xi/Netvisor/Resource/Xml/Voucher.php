<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class Voucher extends Root
{
    public const CALCULATION_MODE_NET = 'net';
    public const CALCULATION_MODE_GROSS = 'gross';

    private $calculationMode;
    private $voucherDate;
    private $number;
    private $voucherClass;

    /**
     * @XmlList(inline = true, entry = "voucherline")
     */
    private $voucherLines = array();

    /**
     * @param String $voucherClass
     * @param String $calculationMode
     * @param \DateTime $voucherDate
     */
    public function __construct($voucherClass, $calculationMode, \DateTime $voucherDate)
    {
        parent::__construct();

        $this->voucherClass = $voucherClass;
        $this->calculationMode = $calculationMode;
        $this->voucherDate = new AttributeElement($voucherDate->format('Y-m-d'), array('format' => 'ansi'));
    }

    /**
     * @param VoucherLine $line
     * @return self
     */
    public function addVoucherLine(VoucherLine $line)
    {
        $this->voucherLines[] = $line;
        return $this;
    }

    /**
     * @param string $number
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return string
     */
    public function getDtdPath()
    {
        return $this->getDtdFile('accounting.dtd');
    }

    /**
     * @return string
     */
    protected function getXmlName()
    {
        return 'voucher';
    }
}
