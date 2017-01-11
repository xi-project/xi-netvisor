<?php

namespace Xi\Netvisor;

use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Netvisor;
use Xi\Netvisor\Config;
use Xi\Netvisor\Resource\Xml\SalesInvoice;
use GuzzleHttp\Client;
use Xi\Netvisor\Resource\Xml\TestResource;
use GuzzleHttp\Psr7\Response;

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
        $this->client = $this->getMockBuilder('GuzzleHttp\Client')
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

        $this->netvisor = new Netvisor($this->client, $this->config, new Validate());
    }

    /**
     * @test
     */
    public function builds()
    {
        $this->assertInstanceOf('Xi\Netvisor\Netvisor', Netvisor::build($this->config));
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

        $netvisor = new Netvisor($this->client, $config, new Validate());

        $this->assertNull(
            $netvisor->requestWithBody(new TestResource(), 'service')
        );
    }

    /**
     * @test
     */
    public function throwsIfXmlIsNotValid()
    {
        $this->setExpectedException('Xi\Netvisor\Exception\NetvisorException', 'XML is not valid according to DTD');

        $this->netvisor->requestWithBody(new TestResource(), 'service');
    }

    /**
     * @test
     */
    public function requestsIfDtdValidationPasses()
    {
        $resource = new TestResource();
        $resource->setValue('value');

        $this->client->expects($this->once())
            ->method('request')
            ->with($this->anything())
            ->will($this->returnValue(
                new Response('200', array(), 'lus')
            ));

        $this->assertEquals('lus', $this->netvisor->requestWithBody($resource, 'service'));
    }

    /**
     * TODO: Betterize test and/or Netvisor structure.
     *
     * @test
     */
    public function sendInvoiceSendsRequest()
    {
        $validate = $this->getMockBuilder('Xi\Netvisor\Component\Validate')
            ->disableOriginalConstructor()
            ->getMock();

        $validate->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $netvisor = new Netvisor($this->client, $this->config, $validate);

        $this->client->expects($this->once())
            ->method('request')
            ->will($this->returnValue(
                new Response('200', array(), 'lus')
            ));

        $invoice = $this->getMockBuilder('Xi\Netvisor\Resource\Xml\SalesInvoice')
            ->disableOriginalConstructor()
            ->getMock();

        $invoice->expects($this->once())
            ->method('getDtdPath')
            ->will($this->returnValue(__DIR__ . '/Resource/Dtd/test.dtd'));

        $this->assertEquals('lus', $netvisor->sendInvoice($invoice));
    }

    /**
     * @test
     */
    public function processInvoiceToWorkWithNetvisor()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<salesinvoicedate><![CDATA[2014-02-17]]></salesinvoicedate>";

        $this->assertEquals('<salesinvoicedate>2014-02-17</salesinvoicedate>', $this->netvisor->processXml($xml));
    }
}
