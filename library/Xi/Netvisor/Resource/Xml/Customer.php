<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;

class Customer extends Root
{
    private $customerBaseInformation;
    private $customerFinvoiceDetails;

    public function __construct(
        CustomerBaseInformation $customerBaseInformation,
        CustomerFinvoiceDetails $customerFinvoiceDetails = null
    ) {
        parent::__construct();
        
        $this->customerBaseInformation = $customerBaseInformation;
        $this->customerFinvoiceDetails = $customerFinvoiceDetails;
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('customer.dtd');
    }

    protected function getXmlName()
    {
        return 'customer';
    }
}
