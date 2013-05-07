<?php

namespace Xi\Netvisor\Resource;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

abstract class Resource
{
    /**
     * @var string
     */
    protected $xml;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        // TODO: Convert array to XML, append DTD and validate against it, set XML as an instance variable.
        $this->xml = $this->toXml($data);
    }

    /**
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * @param  array  $data
     * @return string
     */
    private function toXml(array $data)
    {
        $encoder = new XmlEncoder('root');

        return $encoder->encode($data, 'xml');
    }
}