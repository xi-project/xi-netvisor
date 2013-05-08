<?php

namespace Xi\Netvisor;

use Xi\Netvisor\Netvisor;
use Xi\Netvisor\Component\Config;
use Guzzle\Http\Client;

class NetvisorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Netvisor
     */
    private $netvisor;

    /**
     * @var Client
     */
    private $client;

    /**
     * @test
     */
    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $config = new Config(
            true,
            'host',
            'sender',
            'customerId',
            'partnerId',
            'language',
            'organizationId',
            'userKey',
            'partnerKey'
        );

        $this->netvisor = new Netvisor($this->client, $config);
    }

    /**
     * @test
     */
    public function returnsNullIfNotEnabled()
    {
        $this->netvisor;
    }
}