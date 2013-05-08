<?php

namespace Xi\Netvisor;

use Xi\Netvisor\Netvisor;
use Xi\Netvisor\Component\Config;
use Guzzle\Http\Client;
use Xi\Netvisor\Resource\Xml\TestRoot;
use Guzzle\Http\Message\Response;

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
     * @var Config
     */
    private $config;

    /**
     * @test
     */
    public function setUp()
    {
        $this->client = $this->getMockBuilder('Guzzle\Http\Client')
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = new Config(
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

        $this->netvisor = new Netvisor($this->client, $this->config);
    }

    /**
     * @test
     */
    public function returnsNullIfNotEnabled()
    {
        $config = new Config(
            false,
            'host',
            'sender',
            'customerId',
            'partnerId',
            'language',
            'organizationId',
            'userKey',
            'partnerKey'
        );

        $netvisor = new Netvisor($this->client, $config);

        $this->assertNull(
            $netvisor->request(new TestRoot(), 'service')
        );
    }

    /**
     * @test
     */
    public function throwsIfXmlIsNotValid()
    {
        $root = new TestRoot();

        $this->setExpectedException('Xi\Netvisor\Exception\NetvisorException', 'XML is not valid according to DTD');

        $this->netvisor->request($root, 'service');
    }

    /**
     * @test
     */
    public function requestsIfDtdValidationPasses()
    {
        $root = new TestRoot();
        $root->setValue('value');
        $root->setInner('innervalue', 'innerattribute');

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->anything())
            ->will($this->returnValue(
                new Response('200', array(), 'lus')
            ));

        $this->assertEquals('lus', $this->netvisor->request($root, 'service'));
    }

    /**
     * @test
     */
    public function builds()
    {
        $this->assertInstanceOf('Xi\Netvisor\Netvisor', Netvisor::build($this->config));
    }
}
