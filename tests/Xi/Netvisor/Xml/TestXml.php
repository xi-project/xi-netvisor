<?php

namespace Xi\Netvisor\Xml;

use Xi\Netvisor\Xml\Root;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\XmlValue;
use JMS\Serializer\Annotation\XmlRoot;

/** @XmlRoot("root") */
class TestXml extends Root
{
    /**
     * @XmlAttribute
     */
    public $attribute;

    /**
     * @XmlValue
     */
    public $value;
}