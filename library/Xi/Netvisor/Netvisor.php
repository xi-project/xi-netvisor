<?php
namespace Xi\Netvisor;

use Guzzle\Http\Client;
use Xi\Netvisor\Component\Config;
use Xi\Netvisor\Component\Request;

/**
 * Connects to Netvisor-interface via HTTP.
 * Authentication is based on HTTP headers.
 * A single XML file is sent to the server.
 * The server returns a XML response that contains the transaction status.
 *
 * @category Xi
 * @package  Netvisor
 * @author   Panu Leppäniemi <me@panuleppaniemi.com>
 * @author   Henri Vesala    <henri.vesala@gmail.fi>
 * @author   Petri Koivula   <petri.koivula@iki.fi>
 */
class Netvisor
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     * @param Config $config
     */
    public function __construct(
        Client $client,
        Config $config
    ) {
        $this->client = $client;
        $this->config = $config;
    }

    public function addVoucher(Voucher $voucher)
    {
        /*$processor = new Processor();
        $xml = $processor->process($voucher);

        $validate = new Validate();
        $validate->isValid($voucher, $xml);*/ // @throws
    }

    private function request()
    {
        $request = new Request($this->client, $this->config);
    }
}
