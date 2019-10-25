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
    private $customerGroupName;
    private $emailInvoicingAddress;
    private $internalIdentifier;

    /**
     * @param string $externalIdentifier
     * @param string $name
     * @param string $streetAddress
     * @param string $city
     * @param string $postNumber
     * @param string $country
     * @param string|null $customerGroupName
     * @param string|null $emailInvoicingAddress
     * @param string|null $internalIdentifier
     */
    public function __construct(
        $externalIdentifier,
        $name,
        $streetAddress,
        $city,
        $postNumber,
        $country,
        $customerGroupName = null,
        $emailInvoicingAddress = null,
        $internalIdentifier = null
    ) {
        $this->externalIdentifier = $externalIdentifier;
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postNumber = $postNumber;
        $this->country = $country;
        $this->customerGroupName = $customerGroupName;
        $this->emailInvoicingAddress = $emailInvoicingAddress;
        $this->internalIdentifier = $internalIdentifier;
    }
}
