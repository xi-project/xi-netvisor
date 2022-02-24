<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\VoucherLine;
use Xi\Netvisor\XmlTestCase;

class VoucherLineTest extends XmlTestCase
{
    /**
     * @var VoucherLine
     */
    private $voucherLine;

    public function setUp(): void
    {
        parent::setUp();

        $this->voucherLine = new VoucherLine(0, 0, 0);
    }

    public function testXmlHasRequiredValues()
    {
        $lineSum = 123;
        $account = 321;
        $vatPercent = 24;

        $voucherLine = new VoucherLine($lineSum, $account, $vatPercent);

        $xml = $this->toXml($voucherLine);

        $this->assertXmlContainsTagWithValue('linesum', $lineSum, $xml);
        $this->assertXmlContainsTagWithAttributes(
            'linesum',
            array('type' => VoucherLine::UNIT_PRICE_TYPE_WITHOUT_VAT),
            $xml
        );

        $this->assertXmlContainsTagWithValue('accountnumber', $account, $xml);

        $this->assertXmlContainsTagWithValue('vatpercent', $vatPercent, $xml);
        $this->assertXmlContainsTagWithAttributes(
            'vatpercent',
            array('vatcode' => VoucherLine::VAT_CODE_KOMY),
            $xml
        );
    }

    public function testXmlHasAddedDimensionLines()
    {
        $name = 'Test dimension name';
        $item = 'Test dimension item';
        $name2 = 'Another test dimension name';
        $item2 = 'Another test dimension item';

        $this->voucherLine->addDimension($name, $item);
        $this->voucherLine->addDimension($name2, $item2);

        $xml = $this->toXml($this->voucherLine);

        $this->assertSame(2, substr_count($xml, '<dimensionname>'));
        $this->assertContains($name, $xml);
        $this->assertContains($item, $xml);
        $this->assertContains($name2, $xml);
        $this->assertContains($item, $xml);
    }

    /**
     * @dataProvider lineSumTypeProvider
     */
    public function testSetLineSumType($type)
    {
        // Default
        $xml = $this->toXml($this->voucherLine);
        $this->assertXmlContainsTagWithAttributes(
            'linesum',
            array('type' => VoucherLine::UNIT_PRICE_TYPE_WITHOUT_VAT),
            $xml
        );

        // Setted value
        $this->voucherLine->setLineSumType($type);
        $xml = $this->toXml($this->voucherLine);
        $this->assertXmlContainsTagWithAttributes('linesum', array('type' => $type), $xml);
    }

    public function lineSumTypeProvider()
    {
        return [
            [VoucherLine::UNIT_PRICE_TYPE_WITH_VAT],
            [VoucherLine::UNIT_PRICE_TYPE_WITHOUT_VAT],
        ];
    }

    /**
     * @dataProvider setVatCodeProvider
     */
    public function testSetVatCode($code)
    {
        // Default
        $xml = $this->toXml($this->voucherLine);
        $this->assertXmlContainsTagWithAttributes(
            'vatpercent',
            array('vatcode' => VoucherLine::VAT_CODE_KOMY),
            $xml
        );

        // Setted value
        $this->voucherLine->setVatCode($code);
        $xml = $this->toXml($this->voucherLine);

        $this->assertXmlContainsTagWithAttributes(
            'vatpercent',
            array('vatcode' => $code),
            $xml
        );
    }

    public function setVatCodeProvider()
    {
        return [
            [VoucherLine::VAT_CODE_KOMY],
            [VoucherLine::VAT_CODE_NONE],
        ];
    }

    public function testSetDescription()
    {
        $description = md5(time());
        $this->voucherLine->setDescription($description);
        $xml = $this->toXml($this->voucherLine);
        $this->assertXmlContainsTagWithValue('description', $description, $xml);

        // Test max length
        while (strlen($description) <= 255) {
            $description .= $description;
        }

        $this->voucherLine->setDescription($description);
        $xml = $this->toXml($this->voucherLine);

        $this->assertXmlContainsTagWithValue('description', substr($description, 0, 255), $xml);
        $this->assertNotContains($description, $xml);
    }
}
