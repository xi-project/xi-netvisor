<?php

namespace Xi\Netvisor\Resource\Xml\Component;

use JMS\Serializer\Annotation\XmlAttributeMap;
use JMS\Serializer\Annotation\Inline;

class Element
{
    /**
     * @Inline
     */
    private $value;

    /**
     * @XmlAttributeMap
     */
    private $attributes;

    /**
     * @param string $value
     * @param array  $attributes
     */
    public function __construct($value, $attributes)
    {
        $this->value = $value;
        $this->attributes = $attributes;
    }
}
