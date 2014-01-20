<?php

namespace Xi\Netvisor\Resource\Xml\Component;

abstract class Root
{
    /**
     * Return a file path to a DTD file
     * which should be used for XML validation.
     *
     * @return string
     */
    abstract public function getDtdPath();

    /**
     * @return string
     */
    protected function getDtdFile($filename)
    {
        return __DIR__ . '/../Dtd/' . $filename;
    }
}
