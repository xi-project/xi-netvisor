<?php

namespace Xi\Netvisor\Resource\Xml;

class CustomerBaseInformation
{
    private $externalIdentifier;
    private $name;
    private $streetAddress;
    private $city;
    private $postNumber;
    private $country;
    private $invoicingLanguage;

    /**
     * @param string $externalIdentifier
     * @param string $name
     * @param string $streetAddress
     * @param string $city
     * @param string $postNumber
     * @param string $country
     * @param string $invoicingLanguage
     */
    public function __construct(
        $externalIdentifier,
        $name,
        $streetAddress,
        $city,
        $postNumber,
        $country,
        $invoicingLanguage = 'FI'
    ) {
        $this->externalIdentifier = $externalIdentifier;
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postNumber = $postNumber;
        $this->country = $country;
        $this->invoicingLanguage = $invoicingLanguage;
    }
}
