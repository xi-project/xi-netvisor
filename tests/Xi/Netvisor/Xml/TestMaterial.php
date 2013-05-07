<?php

namespace Xi\Netvisor\Xml;

use Xi\Netvisor\Xml\Material;
use JMS\Serializer\Annotation\XmlAttribute;

class TestMaterial extends Material
{
    /**
     * @XmlAttribute
     */
    public $attribute;

    /**
     * @XmlAttribute
     */
    public $value;
}