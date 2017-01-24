<?php
namespace Xi\Netvisor;

use DateTime;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use Xi\Netvisor\Config;
use Xi\Netvisor\Component\Request;
use Xi\Netvisor\Exception\NetvisorException;
use Xi\Netvisor\Component\Validate;
use Xi\Netvisor\Resource\Xml\Component\Root;
use JMS\Serializer\Serializer;
use Xi\Netvisor\Resource\Xml\Customer;
use Xi\Netvisor\Resource\Xml\SalesInvoice;
use Xi\Netvisor\Serializer\Naming\LowercaseNamingStrategy;

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
 * @author   Artur Gajewski  <info@arturgajewski.com>
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
     * @var Validate
     */
    private $validate;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Initialize with Netvisor::build()
     *
     * @param Client   $client
     * @param Config   $config
     * @param Validate $validate
     */
    public function __construct(
        Client $client,
        Config $config,
        Validate $validate
    ) {
        $this->client     = $client;
        $this->config     = $config;
        $this->validate   = $validate;
        $this->serializer = $this->createSerializer();
    }

    /**
     * Builds a default instance of this class.
     *
     * @param  Config   $config
     * @return Netvisor
     */
    public static function build(Config $config)
    {
        return new Netvisor(new Client(), $config, new Validate());
    }

    /**
     * @param  SalesInvoice $invoice
     * @param  String       $language
     * @return null|string
     */
    public function sendInvoice(SalesInvoice $invoice, $language = null)
    {
        return $this->requestWithBody($invoice, 'salesinvoice', array(), $language);
    }

    /**
     * @param Customer $customer
     * @return null|string
     */
    public function sendCustomer(Customer $customer)
    {
        return $this->requestWithBody($customer, 'customer', ['method' => 'add']);
    }

    /**
     * List customers, optionally filtered by a keyword.
     *
     * The keyword matches Netvisor fields
     * Name, Customer Code, Organization identifier, CoName
     *
     * @param null|string $keyword
     * @return null|string
     */
    public function getCustomers($keyword = null)
    {
        return $this->get(
            'customerlist',
            [
                'keyword' => $keyword,
            ]
        );
    }

    /**
     * List customers that have changed since given date.
     *
     * Giving a keyword would override the changed since parameter.
     *
     * @param DateTime $changedSince
     * @return null|string
     */
    public function getCustomersChangedSince(DateTime $changedSince)
    {
        return $this->get(
            'customerlist',
            [
                'changedsince' => $changedSince->format('Y-m-d'),
            ]
        );
    }

    /**
     * Get details for a customer identified by Netvisor id.
     *
     * @param int $id
     * @return null|string
     */
    public function getCustomer($id)
    {
        return $this->get(
            'getcustomer',
            [
                'id' => $id,
            ]
        );
    }

    /**
     * Get details for a product identified by Netvisor id.
     *
     * @param int $id
     * @return null|string
     */
    public function getProduct($id)
    {
        return $this->get(
            'getproduct',
            [
                'id' => $id,
            ]
        );
    }

    /**
     * @param string  $service
     * @param array   $params
     * @return null|string
     */
    private function get($service, array $params = [])
    {
        if (!$this->config->isEnabled()) {
            return null;
        }

        $request = new Request($this->client, $this->config);

        return $request->get($service, $params);
    }

    /**
     * @param  Root              $root
     * @param  string            $service
     * @param  array             $params
     * @param  string            $language
     * @return null|string
     * @throws NetvisorException
     */
    public function requestWithBody(Root $root, $service, array $params = [], $language = null)
    {
        if (!$this->config->isEnabled()) {
            return null;
        }

        $xml = $this->serializer->serialize($root->getSerializableObject(), 'xml');

        if (!$this->validate->isValid($xml, $root->getDtdPath())) {
            throw new NetvisorException('XML is not valid according to DTD');
        }

        if ($language !== null) {
            $this->config->setLanguage($language);
        }

        $request = new Request($this->client, $this->config);

        return $request->post($this->processXml($xml), $service, $params);
    }

    /**
     * @return Serializer
     */
    private function createSerializer()
    {
        $builder = SerializerBuilder::create();
        $builder->setPropertyNamingStrategy(new LowercaseNamingStrategy());

        return $builder->build();
    }

    /**
     * Process given XML into Netvisor specific format
     *
     * @param  string $xml
     * @return string
     */
    public function processXml($xml)
    {
        $xml = str_replace("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n", "", $xml);

        return $xml;
    }
}
