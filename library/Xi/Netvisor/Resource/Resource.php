<?php

namespace Xi\Netvisor\Resource;

abstract class Resource
{
    /**
     * @var String
     */
    protected $xml;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        // TODO: Render XML Twig template, validate against DTD, set as an instance variable
    }

    abstract public function add();

    abstract public function get();

    abstract public function all();

    abstract public function edit();
}