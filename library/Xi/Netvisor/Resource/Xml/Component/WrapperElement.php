<?php

namespace Xi\Netvisor\Resource\Xml\Component;

use JMS\Serializer\Annotation\XmlKeyValuePairs;
use JMS\Serializer\Annotation\Inline;

class WrapperElement
{
    /**
     * @XmlKeyValuePairs
     * @Inline
     */
    private $value;

    /**
     * @param string $elementName
     * @param mixed  $value
     */
    public function __construct($elementName, $value)
    {
        $this->value = array($elementName => $value);
    }
}
