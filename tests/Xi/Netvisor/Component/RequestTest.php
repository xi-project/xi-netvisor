<?php

namespace Xi\Netvisor\Component;

use Xi\Netvisor\Component\Request;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Client;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = $this->getMockBuilder('Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $config = new Config(
            true,
            'http://integration.netvisor.fi',
            'sender',
            'customerId',
            'partnerId',
            'language',
            'organizationId',
            'userKey',
            'partnerKey'
        );

        $this->request = new Request($this->client, $config);
    }

    /**
     * @test
     */
    public function requests()
    {
        $this->client->expects($this->once())
            ->method('createRequest')
            ->with(
                RequestInterface::POST,
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            );

        $this->request->request(
            '<?xml>',
            'accounting'
        );
    }
}