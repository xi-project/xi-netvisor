<?php

namespace Xi\Netvisor\Resource\Xml;

class Dimension
{
    private $dimensionName;
    private $dimensionItem;

    /**
     * @param $dimensionName
     * @param $dimensionItem
     */
    public function __construct($dimensionName, $dimensionItem)
    {
        $this->dimensionName = $dimensionName;
        $this->dimensionItem = $dimensionItem;
    }
}
