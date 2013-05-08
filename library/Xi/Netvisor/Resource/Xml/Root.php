<?php

namespace Xi\Netvisor\Resource\Xml;

abstract class Root
{
    /**
     * Return a file path to a DTD file
     * which should be used for XML validation.
     *
     * @return string
     */
    abstract public function getDtdPath();
}
