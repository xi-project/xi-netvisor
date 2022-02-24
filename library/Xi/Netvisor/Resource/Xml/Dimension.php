<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Component\AttributeElement;

class Dimension
{
    private $dimensionname;
    private $dimensionitem;

    /**
     * @param string $dimensionname
     * @param string $dimensionitem
     */
    public function __construct($dimensionname, $dimensionitem)
    {
        $this->dimensionname = $dimensionname;
        $this->dimensionitem = $dimensionitem;
    }
}
