<?php

namespace Xi\Netvisor\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TestResource
     */
    private $resource;

    public function setUp()
    {
        $this->markTestIncomplete();

        $this->resource = new TestResource(array(
            '@attribute' => '1',
            'children' => array(
                'row' => array(
                    '@attribute' => 'value',
                    '#' => 'value'
                )
            )
        ));

        echo $this->resource->getXml();
    }

    /**
     * @test
     */
    public function generatesXml()
    {
        $this->assertStringStartsWith('<?xml', $this->resource->getXml());
    }

    public function appendsDtdAndValidatesXml()
    {
        $voucher = new Voucher();

        $voucher->calculationMode = 'net';
        $voucher->voucherDate = '2009-1-1';

        $voucher->addVoucherLine();

        $data['calculationMode'] = 'net';
        $data['voucherDate'] = '2009-1-1';
    }
}