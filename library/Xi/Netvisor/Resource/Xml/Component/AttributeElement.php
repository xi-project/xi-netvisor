<?php

namespace Xi\Netvisor\Resource\Xml\Component;

use JMS\Serializer\Annotation\XmlAttributeMap;
use JMS\Serializer\Annotation\Inline;

class AttributeElement
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

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param string $attribute
     * @param array  $value
     * @return self
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        return $this;
    }
}
