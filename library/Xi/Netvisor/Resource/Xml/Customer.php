<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;

class Customer extends Root
{
    private $externalIdentifier;
    private $name;
    private $streetAddress;
    private $city;
    private $postNumber;
    private $country;
    private $finvoiceAddress;

    /**
     * @param string $externalIdentifier
     * @param string $name
     * @param string $streetAddress
     * @param string $city
     * @param string $postNumber
     * @param string $country
     * @param string $finvoiceAddress
     */
    public function __construct(
        $externalIdentifier,
        $name,
        $streetAddress,
        $city,
        $postNumber,
        $country,
        $finvoiceAddress
    ) {
        $this->externalIdentifier = $externalIdentifier;
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postNumber = $postNumber;
        $this->country = $country;
        $this->finvoiceAddress = $finvoiceAddress;
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('customer.dtd');
    }

    protected function getXmlName()
    {
        return 'salesinvoice';
    }
}
