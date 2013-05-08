<?php

namespace Xi\Netvisor;

use Xi\Netvisor\Netvisor;

class NetvisorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function smoke()
    {
        new Netvisor();
    }
}