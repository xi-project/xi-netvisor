<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;

class PurchaseInvoice extends Root
{
    private $invoicenumber;
    private $invoicedate;
    private $valuedate;
    private $duedate;
    private $amount;
    private $comment;

    /**
     * @XmlList(entry = "purchaseinvoiceline")
     */
    private $purchaseinvoicelines = array();

    /**
     * @param int $invoiceNumber
     * @param \DateTime $invoiceDate
     * @param \DateTime $valueDate
     * @param \DateTime $dueDate
     * @param float $amount
     */
    public function __construct(
        $invoiceNumber,
        \DateTime $invoiceDate,
        \DateTime $valueDate,
        \DateTime $dueDate,
        $amount
    ) {
        parent::__construct();

        $this->invoicenumber = $invoiceNumber;
        $this->amount = round($amount, 2);

        $this->invoicedate = new AttributeElement(
            $invoiceDate->format('Y-m-d'),
            array('format' => 'ansi')
        );

        $this->valuedate = new AttributeElement(
            $valueDate->format('Y-m-d'),
            array('format' => 'ansi')
        );

        $this->duedate = new AttributeElement(
            $dueDate->format('Y-m-d'),
            array('format' => 'ansi')
        );
    }

    /**
     * @param PurchaseInvoiceLine $line
     * @return self
     */
    public function addPurchaseInvoiceLine(PurchaseInvoiceLine $line)
    {
        $this->purchaseinvoicelines[] = new WrapperElement('purchaseinvoiceline', $line);
        return $this;
    }

    /**
     * @param string $comment
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = substr($comment, 0, 255);
        return $this;
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('purchaseinvoice.dtd');
    }

    protected function getXmlName()
    {
        return 'purchaseinvoice';
    }
}
