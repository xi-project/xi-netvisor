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
    private $organizationunitnumber;
    private $phonenumber;
    private $email;
    private $isprivatecustomer = 0;

    /**
     * @param string $name
     * @param string $streetAddress
     * @param string $city
     * @param string $postNumber
     * @param string $country
     */
    public function __construct(
        $name,
        $streetAddress,
        $city,
        $postNumber,
        $country
    ) {
        $this->name = $name;
        $this->streetAddress = $streetAddress;
        $this->city = $city;
        $this->postNumber = $postNumber;
        $this->country = $country;
    }

    /**
     * @param string $ovt
     * @return self
     */
    public function setOvt($ovt)
    {
        $this->organizationunitnumber = $ovt;
        return $this;
    }

    /**
     * @param string $number
     * @return self
     */
    public function setPhoneNumber($number)
    {
        $this->phonenumber = $number;
        return $this;
    }

    /**
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $od
     * @return self
     */
    public function setBusinessId($id)
    {
        $this->externalIdentifier = null;
        $this->isprivatecustomer = 0;

        if ($id) {
            $this->externalIdentifier = $id;
            $this->isprivatecustomer = 1;
        }

        return $this;
    }
}
