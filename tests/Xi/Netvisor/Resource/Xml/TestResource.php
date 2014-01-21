<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\Root;

class TestResource extends Root
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

    protected function getXmlName()
    {
        return 'test';
    }
}
