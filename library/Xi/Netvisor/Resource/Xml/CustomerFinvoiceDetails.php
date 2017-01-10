<?php

namespace Xi\Netvisor\Resource\Xml;

class CustomerFinvoiceDetails
{
    private $finvoiceAddress;

    /**
     * @param string $finvoiceAddress
     */
    public function __construct(
        $finvoiceAddress
    ) {
        $this->finvoiceAddress = $finvoiceAddress;
    }
}
