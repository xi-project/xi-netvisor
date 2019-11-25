<?php

namespace Xi\Netvisor\Resource\Xml;

class CustomerAdditionalInformation
{
    private $invoicingLanguage;

    /**
     * @param string $invoicingLanguage
     */
    public function __construct(
        $invoicingLanguage = 'FI'
    ) {
        $this->invoicingLanguage = $invoicingLanguage;
    }
}
