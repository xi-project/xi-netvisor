<?php

namespace Xi\Netvisor\Component;

use Guzzle\Http\Client;

class Request
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}