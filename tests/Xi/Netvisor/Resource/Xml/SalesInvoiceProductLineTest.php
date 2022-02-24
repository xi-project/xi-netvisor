<?php

namespace Xi\Netvisor\Resource\Xml;

use Xi\Netvisor\Resource\Xml\SalesInvoiceProductLine;
use Xi\Netvisor\XmlTestCase;

class SalesInvoiceProductLineTest extends XmlTestCase
{
    private const LONG_PRODUCT_NAmE = 'Product name, which is longer than the limit of 200 characters Will add some lirum larum. Will add some lirum larum. Will add some lirum larum. Will add some lirum larum. Will add some lirum larum. Will add some lirum larum.';
    /**
     * @var SalesInvoiceProductLine
     */
    private $invoiceProductLine;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoiceProductLine = new SalesInvoiceProductLine(
            '100',
            static::LONG_PRODUCT_NAmE,
            '1,23',
            '24',
            '5'
        );
    }

    /**
     * @test
     */
    public function xmlHasRequiredProductLineValues()
    {
        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('productidentifier', '100', $xml);
        $this->assertXmlContainsTagWithAttributes('productidentifier', array('type' => 'netvisor'), $xml);

        $this->assertXmlContainsTagWithValue('productname', substr(static::LONG_PRODUCT_NAmE, 0, 200), $xml);
        $this->assertNotContains(static::LONG_PRODUCT_NAmE, $xml);

        $this->assertXmlContainsTagWithValue('productunitprice', '1,23', $xml);
        $this->assertXmlContainsTagWithAttributes('productunitprice', array('type' => 'net'), $xml);

        $this->assertXmlContainsTagWithValue('productvatpercentage', '24', $xml);
        $this->assertXmlContainsTagWithAttributes('productvatpercentage', array('vatcode' => 'KOMY'), $xml);

        $this->assertXmlContainsTagWithValue('salesinvoiceproductlinequantity', '5', $xml);
    }

    /**
     * @test
     */
    public function xmlHasAddedDimensionLines()
    {
        $name = 'Test dimension name';
        $item = 'Test dimension item';
        $name2 = 'Another test dimension name';
        $item2 = 'Another test dimension item';

        $this->invoiceProductLine->addDimension($name, $item);
        $this->invoiceProductLine->addDimension($name2, $item2);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertSame(2, substr_count($xml, '<dimensionname>'));
        $this->assertContains($name, $xml);
        $this->assertContains($item, $xml);
        $this->assertContains($name2, $xml);
        $this->assertContains($item, $xml);
    }

    /**
     * @dataProvider productIdentifierTypeProvider
     */
    public function testSetProductIdentifierType($type)
    {
        // Default
        $xml = $this->toXml($this->invoiceProductLine);
        $this->assertXmlContainsTagWithAttributes(
            'productidentifier',
            array('type' => SalesInvoiceProductLine::PRODUCT_IDENTIFIER_TYPE_NETVISOR),
            $xml
        );

        // Setted value
        $this->invoiceProductLine->setProductIdentiefierType($type);
        $xml = $this->toXml($this->invoiceProductLine);
        $this->assertXmlContainsTagWithAttributes('productidentifier', array('type' => $type), $xml);
    }

    public function productIdentifierTypeProvider()
    {
        return [
            [SalesInvoiceProductLine::PRODUCT_IDENTIFIER_TYPE_CUSTOMER],
            [SalesInvoiceProductLine::PRODUCT_IDENTIFIER_TYPE_NETVISOR],
        ];
    }

    /**
     * @dataProvider unitPriceTypeProvider
     */
    public function testSetUnitPriceType($type)
    {
        // Default
        $xml = $this->toXml($this->invoiceProductLine);
        $this->assertXmlContainsTagWithAttributes(
            'productunitprice',
            array('type' => SalesInvoiceProductLine::UNIT_PRICE_TYPE_WITHOUT_VAT),
            $xml
        );

        // Setted value
        $this->invoiceProductLine->setUnitPriceType($type);
        $xml = $this->toXml($this->invoiceProductLine);
        $this->assertXmlContainsTagWithAttributes('productunitprice', array('type' => $type), $xml);
    }

    public function unitPriceTypeProvider()
    {
        return [
            [SalesInvoiceProductLine::UNIT_PRICE_TYPE_WITH_VAT],
            [SalesInvoiceProductLine::UNIT_PRICE_TYPE_WITHOUT_VAT],
        ];
    }

    public function testSetFreeText()
    {
        $text = 'Additional information';
        $this->invoiceProductLine->setFreeText($text);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('salesinvoiceproductlinefreetext', $text, $xml);
    }

    public function testSetAccountingAccount()
    {
        $account = 2000;
        $this->invoiceProductLine->setAccountingAccount($account);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('accountingaccountsuggestion', $account, $xml);
    }

    /**
     * @dataProvider setVatCodeProvider
     */
    public function testSetVatCode($code)
    {
        // Default
        $xml = $this->toXml($this->invoiceProductLine);
        $this->assertXmlContainsTagWithAttributes(
            'productvatpercentage',
            array('vatcode' => SalesInvoiceProductLine::VAT_CODE_KOMY),
            $xml
        );

        // Setted value
        $this->invoiceProductLine->setVatCode($code);
        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithAttributes(
            'productvatpercentage',
            array('vatcode' => $code),
            $xml
        );
    }

    public function setVatCodeProvider()
    {
        return [
            [SalesInvoiceProductLine::VAT_CODE_KOMY],
            [SalesInvoiceProductLine::VAT_CODE_NONE],
        ];
    }

    public function testSetDiscountPercentage()
    {
        $discountPercentage = 20;
        $this->invoiceProductLine->setDiscountPercentage($discountPercentage);

        $xml = $this->toXml($this->invoiceProductLine);

        $this->assertXmlContainsTagWithValue('salesinvoiceproductlinediscountpercentage', $discountPercentage, $xml);
    }
}
