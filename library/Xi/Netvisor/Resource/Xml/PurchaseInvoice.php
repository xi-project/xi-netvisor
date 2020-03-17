<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class PurchaseInvoice extends Root
{
    public const INVOICE_SOURCE_FINVOICE = 'finvoice';
    public const INVOICE_SOURCE_MANUAL = 'manual';

    private $invoicenumber;
    private $invoicedate;
    private $invoicesource;
    private $valuedate;
    private $duedate;
    private $vendorname;
    private $vendoraddressline;
    private $vendorpostnumber;
    private $vendorcity;
    private $vendorcountry;
    private $vendorphonenumber;
    private $vendoremail;
    private $amount;
    private $accountnumber;
    private $organizationidentifier;
    private $bankreferencenumber;
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
        $this->purchaseinvoicelines[] = $line;
        return $this;
    }

    /**
     * @param string $bankAccount
     * @param string $businessId
     * @param string $name
     * @param string $streetAddress
     * @param string $postNumber
     * @param string $city
     * @param string $countryCode
     * @param string $phone
     * @param string $email
     *
     * @return self
     */
    public function setVendorDetails(
        $bankAccount = null,
        $businessId = null,
        $name = null,
        $streetAddress = null,
        $postNumber = null,
        $city = null,
        $countryCode = null,
        $phone = null,
        $email = null
    ) {
        $this->accountnumber = $bankAccount ?: null;
        $this->organizationidentifier = $businessId ?: null;
        $this->vendorname = $name ? substr($name, 0, 250) : null;
        $this->vendoraddressline = $streetAddress ? substr($streetAddress, 0, 80) : null;
        $this->vendorpostnumber = $postNumber ? substr($postNumber, 0, 50) : null;
        $this->vendorcity = $city ? substr($city, 0, 50) : null;
        $this->vendorcountry = $countryCode ? substr($countryCode, 0, 2) : null;
        $this->vendorphonenumber = $phone ? substr($phone, 0, 80) : null;
        $this->vendoremail = $email ? substr($email, 0, 80) : null;

        return $this;
    }

    /**
     * @param string $reference
     * @return self
     */
    public function setBankReferenceNumber($reference)
    {
        $this->bankreferencenumber = $reference;
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

    /**
     * @param string $source
     * @return self
     */
    public function setInvoiceSource($source)
    {
        $allowed = [
            static::INVOICE_SOURCE_FINVOICE,
            static::INVOICE_SOURCE_MANUAL,
        ];

        if (!in_array($source, $allowed)) {
            throw new \Exception('Invalid invoice source: ' . $source);
        }

        $this->invoicesource = $source;
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
