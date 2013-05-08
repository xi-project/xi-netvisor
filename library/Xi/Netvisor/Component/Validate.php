<?php

namespace Xi\Netvisor\Component;

use Xi\Netvisor\Xml\Root;

class Validate
{
    /**
     * Validates the given XML against DTD.
     *
     * @param  string  $xml
     * @param  string  $filepath to DTD
     * @return boolean
     */
    public function isValid($xml, $filepath)
    {
        $xml = explode("\n", $xml);
        $dtd = @file_get_contents($filepath);

        if ($dtd) {
            $xml[0] .= "\n" . $dtd;
        }

        $dom = new \DOMDocument();
        $dom->loadXML(implode("\n", $xml));

        try {
            return $dom->validate();
        } catch (\Exception $e) {
            return false;
        }
    }
}