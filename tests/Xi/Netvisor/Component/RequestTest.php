<?php

namespace Xi\Netvisor\Component;

use GuzzleHttp\Psr7\Response;
use Xi\Netvisor\Component\Request;
use Xi\Netvisor\Config;
use GuzzleHttp\Client;

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

        $this->client = $this->getMockBuilder('GuzzleHttp\Client')
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
    public function createsRequest()
    {
        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            )
            ->will($this->returnValue(
                new Response('200', array(), 'hello')
            ));

        $this->request->post(
            '<?xml>',
            'accounting'
        );
    }

    /**
     * @test
     */
    public function throwsExceptionIfResponseStatusIsFailed()
    {
        $xmlResponse = <<<LUS
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<Root>
    <ResponseStatus>
        <Status>FAILED</Status>
        <Status>AUTHENTICATION_FAILED :: Integraatiokumppania ei löydy, katso dokumentaatio</Status>
        <TimeStamp>7.4.2009 13:46:07</TimeStamp>
    </ResponseStatus>
</Root>
LUS;

        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            )
            ->will($this->returnValue(
                new Response('200', array(), $xmlResponse)
            ));

        $this->setExpectedException(
            'Xi\Netvisor\Exception\NetvisorException',
            'AUTHENTICATION_FAILED :: Integraatiokumppania ei löydy, katso dokumentaatio'
        );

        $this->request->post(
            '<?xml>',
            'accounting'
        );
    }

    /**
     * @test
     */
    public function returnsResponseBodyIfResponseStatusIsOK()
    {
        $xmlResponse = <<<LUS
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<Root>
    <ResponseStatus>
        <Status>OK</Status>
        <TimeStamp>7.4.2009 13:37:00</TimeStamp>
    </ResponseStatus>
</Root>
LUS;

        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'POST',
                'http://integration.netvisor.fi/accounting.nv',
                $this->anything()
            )
            ->will($this->returnValue(
                new Response('200', array(), $xmlResponse)
            ));

        $response = $this->request->post(
            '<?xml>',
            'accounting'
        );

        $this->assertEquals($xmlResponse, $response);
    }
}
