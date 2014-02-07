<?php

namespace Xi\Netvisor;

use Xi\Netvisor\Netvisor;
use Xi\Netvisor\Config;
use Xi\Netvisor\Resource\Xml\SalesInvoice;
use Guzzle\Http\Client;
use Xi\Netvisor\Resource\Xml\TestResource;
use Guzzle\Http\Message\Response;

class NetvisorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Netvisor
     */
    private $netvisor;

    /**
     * @var Mock_Validate
     */
    private $validateMock;

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

        $this->validateMock = $this->getMockBuilder('Xi\Netvisor\Component\Validate')
            ->disableOriginalConstructor()
            ->getMock();

        $this->validateMock->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(
                true
            ));

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

        $this->netvisor = new Netvisor($this->client, $this->config, $this->validateMock);
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

        $netvisor = new Netvisor($this->client, $config, $validateMock);

        $this->assertNull(
            $netvisor->request(new TestResource(), 'service')
        );
    }

    /**
     * @test
     */
    public function throwsIfXmlIsNotValid()
    {
        $resource = new TestResource();

        $this->setExpectedException('Xi\Netvisor\Exception\NetvisorException', 'XML is not valid according to DTD');

        $this->netvisor->request($resource, 'service');
    }

    /**
     * @test
     */
    public function requestsIfDtdValidationPasses()
    {
        $resource = new TestResource();
        $resource->setValue('value');

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->anything())
            ->will($this->returnValue(
                new Response('200', array(), 'lus')
            ));

        $this->assertEquals('lus', $this->netvisor->request($resource, 'service'));
    }

    /**
     * @test
     */
    public function sendInvoiceSendsRequest()
    {
        $invoice = $this->getMockBuilder('Xi\Netvisor\Resource\Xml\SalesInvoice')
            ->disableOriginalConstructor()
            ->getMock();

        $invoice->expects($this->once())
            ->method('getDtdPath')
            ->will($this->returnValue(
                __DIR__ . '/Resource/Dtd/test.dtd'
            ));

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->anything())
            ->will($this->returnValue(
                new Response('200', array(), 'lus')
            ));

        $this->assertEquals('lus', $this->netvisor->sendInvoice($invoice));
    }

    /**
     * @test
     */
    public function builds()
    {
        $this->assertInstanceOf('Xi\Netvisor\Netvisor', Netvisor::build($this->config));
    }
}
