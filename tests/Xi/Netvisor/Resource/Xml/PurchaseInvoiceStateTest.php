<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\XmlTestCase;

class PurchaseInvoiceStateTest extends XmlTestCase
{
    /**
     * @var PurchaseInvoiceState
     */
    private $invoiceState;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoiceState = new PurchaseInvoiceState(123, 'approved', false);
    }

    /**
     * @test
     */
    public function hasDtd()
    {
        $this->assertNotNull($this->invoiceState->getDtdPath());
    }

    /**
     * @test
     */
    public function xmlHasRequiredSalesInvoiceValues()
    {
        $netvisorId = 123;
        $status = 'approved';
        $readyForAccounting = false;

        $invoiceState = new PurchaseInvoiceState($netvisorId, $status, $readyForAccounting);

        $xml = $this->toXml($invoiceState->getSerializableObject());

        $this->assertXmlContainsTagWithValue('purchaseinvoicenetvisorkey', $netvisorId, $xml);
        $this->assertXmlContainsTagWithValue('status', $status, $xml);
        $this->assertXmlContainsTagWithValue('isreadyforaccounting', (int) $readyForAccounting, $xml);

        $this->assertXmlIsValid($xml, $invoiceState->getDtdPath());
    }
}
