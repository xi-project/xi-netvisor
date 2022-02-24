<?php

namespace Xi\Netvisor;

use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Netvisor;
use Xi\Netvisor\Config;
use Xi\Netvisor\Resource\Xml\Customer;
use Xi\Netvisor\Resource\Xml\SalesInvoice;
use Xi\Netvisor\Resource\Xml\Voucher;
use GuzzleHttp\Client;
use Xi\Netvisor\Resource\Xml\TestResource;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Xi\Netvisor\Exception\NetvisorException;
use Xi\Netvisor\Filter\SalesInvoicesFilter;
use Xi\Netvisor\Resource\Xml\PurchaseInvoice;
use Xi\Netvisor\Resource\Xml\PurchaseInvoiceState;

class NetvisorTest extends TestCase
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
    public function setUp(): void
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
            $netvisor->requestWithBody(new TestResource(), 'service', array(), null)
        );
    }

    /**
     * @test
     */
    public function throwsIfXmlIsNotValid()
    {
        $this->expectExceptionMessage('XML is not valid according to DTD');
        $this->expectException(NetvisorException::class);
        $this->netvisor->requestWithBody(new TestResource(), 'service', array(), null);
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

        $this->assertEquals('lus', $this->netvisor->requestWithBody($resource, 'service', array(), null));
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

        $invoice->expects($this->once())
            ->method('getSerializableObject')
            ->will($this->returnValue([]));

        $this->assertEquals('lus', $netvisor->sendInvoice($invoice));
    }

    /**
     * @test
     */
    public function processInvoiceToWorkWithNetvisor()
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<salesinvoicedate><![CDATA[2014-02-17]]</salesinvoicedate>";

        $this->assertEquals('<salesinvoicedate><![CDATA[2014-02-17]]</salesinvoicedate>', $this->netvisor->processXml($xml));
    }

    public function testUpdateCustomer()
    {
        // Expected params
        $id = 12345;
        
        $attributes = [
            'method' => 'edit',
            'id' => $id,
        ];

        $customerMock = $this
            ->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock requestWithBody
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['requestWithBody'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('requestWithBody')
            ->with($customerMock, 'customer', $attributes);

        $netvisorMock->updateCustomer($customerMock, $id);
    }

    public function testUpdateInvoice()
    {
        // Expected params
        $id = 12345;
        
        $attributes = [
            'method' => 'edit',
            'id' => $id,
        ];

        $invoiceMock = $this
            ->getMockBuilder(SalesInvoice::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock requestWithBody
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['requestWithBody'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('requestWithBody')
            ->with($invoiceMock, 'salesinvoice', $attributes);

        $netvisorMock->updateInvoice($invoiceMock, $id);
    }

    public function testSendVoucher()
    {
        $voucherMock = $this
            ->getMockBuilder(Voucher::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock requestWithBody
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['requestWithBody'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('requestWithBody')
            ->with($voucherMock, 'accounting');

        $netvisorMock->sendVoucher($voucherMock);
    }

    public function testGetSalesInvoice()
    {
        $id = 1234564;

        // @var Netvisor $netvisorMock
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('get')
            ->with('getsalesinvoice', ['netvisorkey' => $id]);

        $netvisorMock->getSalesInvoice($id);
    }

    public function testGetPurchaseInvoice()
    {
        $id = 123125;
        $requestParams = ['netvisorkey' => $id];

        // @var Netvisor $netvisorMock
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('get')
            ->with('getpurchaseinvoice', $requestParams);

        $netvisorMock->getPurchaseInvoice($id);
    }

    public function testGetVouchers()
    {
        $start = new \DateTime('2000-01-01');
        $end = new \DateTime('2001-01-01');

        // @var Netvisor $netvisorMock
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('get')
            ->with(
                'accountingledger',
                [
                    'startdate' => $start->format('Y-m-d'),
                    'enddate' => $end->format('Y-m-d'),
                ]
            );

        $netvisorMock->getVouchers($start, $end);
    }

    public function testGetVoucher()
    {
        $id = 12345;
        $start = new \DateTime('2000-01-01');
        $end = new \DateTime('2001-01-01');

        // @var Netvisor $netvisorMock
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['getVouchers'])
            ->getMock();

        $netvisorMock
            ->method('getVouchers')
            ->willReturn('<?xml version="1.0" encoding="utf-8" standalone="yes"?>
            <Root>
                <Vouchers>
                    <Voucher>
                        <NetvisorKey>
                            54321
                        </NetvisorKey>
                    </Voucher>
                    <Voucher>
                        <NetvisorKey>
                            ' . $id . '
                        </NetvisorKey>
                    </Voucher>
                </Vouchers>
            </Root>');

        // Not found
        $result = $netvisorMock->getVoucher(999, $start, $end);
        $this->assertNull($result);

        // Found
        $result = $netvisorMock->getVoucher($id, $start, $end);
        $voucher = new \SimpleXMLElement($result);

        $this->assertSame((int) $voucher->NetvisorKey, $id);
    }

    public function testSendPurchaseInvoice()
    {
        $purchaseInvoiceMock = $this
            ->getMockBuilder(PurchaseInvoice::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock requestWithBody
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['requestWithBody'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('requestWithBody')
            ->with($purchaseInvoiceMock, 'purchaseinvoice');

        $netvisorMock->sendPurchaseInvoice($purchaseInvoiceMock);
    }

    public function testUpdatePurchaseInvoiceState()
    {
        $purchaseInvoiceStateMock = $this
            ->getMockBuilder(PurchaseInvoiceState::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock requestWithBody
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['requestWithBody'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('requestWithBody')
            ->with($purchaseInvoiceStateMock, 'purchaseinvoicepostingdata');

        $netvisorMock->updatePurchaseInvoiceState($purchaseInvoiceStateMock);
    }

    public function testGetSalesInvoices()
    {
        $date = new \DateTime('2000-01-01');
        $lastInvoiceId = 200;

        $filter = new SalesInvoicesFilter();
        $filter->setGreaterThanId($lastInvoiceId);
        $filter->setModifiedAfterDate($date);

        // @var Netvisor $netvisorMock
        $netvisorMock = $this
            ->getMockBuilder(Netvisor::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $netvisorMock
            ->expects($this->once())
            ->method('get')
            ->with(
                'salesinvoicelist',
                [
                    'lastmodifiedstart' => $date->format('Y-m-d'),
                    'invoicesabovenetvisorkey' => $lastInvoiceId,
                ]
            );

        $netvisorMock->getSalesInvoices($filter);
    }
}
