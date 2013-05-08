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
        $xml = $this->insertDtd($xml, $filepath);

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        try {
            return $dom->validate();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param  string $xml
     * @param  string $filepath
     * @return string
     */
    private function insertDtd($xml, $filepath)
    {
        $dtd = @file_get_contents($filepath);

        $xml = explode("\n", $xml);
        $xml[0] .= "\n" . $dtd;

        return implode("\n", $xml);
    }
}