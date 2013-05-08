<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Root;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlValue;

/** @XmlRoot("root") */
class TestRoot extends Root
{
    protected $value;

    protected $inner;

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $value
     * @param string $attr
     */
    public function setInner($value, $attr)
    {
        $inner = new TestRootInner();
        $inner->setValue($value);
        $inner->setAttr($attr);

        $this->inner= $inner;
    }

    /**
     * @inheritdoc
     */
    public function getDtdPath()
    {
        return __DIR__ . '/../Dtd/test.dtd';
    }
}

class TestRootInner
{
    /**
     * @XmlValue
     */
    protected $value;

    /**
     * @XmlAttribute
     */
    protected $attr;

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setAttr($value)
    {
        $this->attr = $value;
    }
}
