<?php

namespace Xi\Netvisor;

class Config
{
    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $customerId;

    /**
     * @var string
     */
    private $partnerId;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $organizationId;

    /**
     * @var string
     */
    private $userKey;

    /**
     * @var string
     */
    private $partnerKey;

    /**
     * @param boolean $enabled
     * @param string  $host
     * @param string  $sender
     * @param string  $customerId
     * @param string  $partnerId
     * @param string  $language
     * @param string  $organizationId
     * @param string  $userKey
     * @param string  $partnerKey
     */
    public function __construct(
        $enabled,
        $host,
        $sender,
        $customerId,
        $partnerId,
        $language,
        $organizationId,
        $userKey,
        $partnerKey
    ) {
        $this->enabled = $enabled;
        $this->host = $host;
        $this->sender = $sender;
        $this->customerId = $customerId;
        $this->partnerId = $partnerId;
        $this->language = $language;
        $this->organizationId = $organizationId;
        $this->userKey = $userKey;
        $this->partnerKey = $partnerKey;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * @return string
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * @return string
     */
    public function getPartnerKey()
    {
        return $this->partnerKey;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return string
     */
    public function getUserKey()
    {
        return $this->userKey;
    }
}
