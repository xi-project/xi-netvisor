<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;
use JMS\Serializer\Annotation\XmlRoot;

/** @XmlRoot("root") */
class TestRoot extends Root
{
    protected $value;

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getDtdPath()
    {
        return __DIR__ . '/../Dtd/test.dtd';
    }
}
