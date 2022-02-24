<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\Voucher;
use Xi\Netvisor\XmlTestCase;

class VoucherTest extends XmlTestCase
{
    /**
     * @var Voucher
     */
    private $voucher;

    public function setUp(): void
    {
        parent::setUp();

        $this->voucher = new Voucher('Myyntilasku', Voucher::CALCULATION_MODE_WITH_VAT, new \DateTime());
    }

    public function testHasDtd()
    {
        $this->assertNotNull($this->voucher->getDtdPath());
    }

    public function testXmlHasRequiredValues()
    {
        $type = 'Myyntilasku';
        $mode = Voucher::CALCULATION_MODE_WITH_VAT;
        $date = new \DateTime();

        $voucher = new Voucher($type, $mode, $date);

        $xml = $this->toXml($voucher->getSerializableObject());

        $this->assertXmlContainsTagWithValue('voucherclass', $type, $xml);
        $this->assertXmlContainsTagWithValue('calculationmode', $mode, $xml);
        $this->assertXmlContainsTagWithAttributes('voucherdate', array('format' => 'ansi'), $xml);
    }

    public function testXmlHasAddedVoucherLines()
    {
        $this->voucher->addVoucherLine(new VoucherLine(0, 0, 0));
        $this->voucher->addVoucherLine(new VoucherLine(100, 1, 24));

        $xml = $this->toXml($this->voucher->getSerializableObject());

        $this->assertContains('voucherline', $xml);
        $this->assertXmlContainsTagWithValue('linesum', 0, $xml);
        $this->assertSame(2, substr_count($xml, '<voucherline>'));

        $this->assertXmlIsValid($xml, $this->voucher->getDtdPath());
    }

    public function testSetNumber()
    {
        $number = '0123456';
    
        $this->voucher->setNumber($number);
        $xml = $this->toXml($this->voucher->getSerializableObject());
        $this->assertXmlContainsTagWithValue('number', $number, $xml);
    }

    public function testSetDescription()
    {
        $description = 'Some description';
    
        $this->voucher->setDescription($description);
        $xml = $this->toXml($this->voucher->getSerializableObject());
        $this->assertXmlContainsTagWithValue('description', $description, $xml);
    }
}
