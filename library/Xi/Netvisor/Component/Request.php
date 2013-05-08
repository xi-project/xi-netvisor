<?php

namespace Xi\Netvisor\Component;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Message\RequestInterface;

class Request
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param Client $client
     */
    public function __construct(HttpClient $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * Makes a request to Netvisor and returns a response.
     *
     * @param  string $xml
     * @param  string $service
     * @return Result
     */
    public function request($xml, $service, $method = null, $id = null)
    {
        $url     = $this->createUrl($service, $method, $id);
        $headers = $this->createHeaders($url);
        $request = $this->client->createRequest(RequestInterface::POST, $url, $headers);
        

        $this->client->send(array($request));
    }

    /**
     * @param  string  $service
     * @param  string  $method
     * @param  integer $id
     * @return string
     */
    private function createUrl($service, $method = null, $id = null)
    {
        $url = "{$this->config->getHost()}/{$service}.nv";

        $params = array(
            'method' => $method,
            'id' => $id,
        );

        $params = array_filter($params);
        $queryString = http_build_query($params);

        if ($queryString) {
            $url .= '?' . $queryString;
        }

        return $url;
    }

    /**
     * @param  string $url
     * @return array
     */
    private function createHeaders($url)
    {
        $authenticationTransactionId = $this->getAuthenticationTransactionId();
        $authenticationTimestamp     = $this->getAuthenticationTimestamp();

        return array(
            'X-Netvisor-Authentication-Sender'        => $this->config->getSender(),
            'X-Netvisor-Authentication-CustomerId'    => $this->config->getCustomerId(),
            'X-Netvisor-Authentication-PartnerId'     => $this->config->getPartnerId(),
            'X-Netvisor-Authentication-Timestamp'     => $authenticationTimestamp,
            'X-Netvisor-Interface-Language'           => $this->config->getLanguage(),
            'X-Netvisor-Organisation-ID'              => $this->config->getOrganizationId(),
            'X-Netvisor-Authentication-TransactionId' => $authenticationTransactionId,
            'X-Netvisor-Authentication-MAC'           => $this->getAuthenticationMac($url, $authenticationTimestamp, $authenticationTransactionId)
        );
    }

    // Reset the client.
        /*$this->client->resetParameters(true);

        // Start building the client.
        $this->client->setUri($url);

        $authenticationTransactionId = $this->getAuthenticationTransactionId();
        $authenticationTimestamp     = $this->getAuthenticationTimestamp();

        // Set headers which Netvisor demands.
        $this->client->setHeaders(array(
            'X-Netvisor-Authentication-Sender'        => $this->config->interface->sender,
            'X-Netvisor-Authentication-CustomerId'    => $this->config->interface->customerId,
            'X-Netvisor-Authentication-PartnerId'     => $this->config->interface->partnerId,
            'X-Netvisor-Authentication-Timestamp'     => $authenticationTimestamp,
            'X-Netvisor-Interface-Language'           => $this->config->interface->language,
            'X-Netvisor-Organisation-ID'              => $this->config->interface->organizationId,
            'X-Netvisor-Authentication-TransactionId' => $authenticationTransactionId,
            'X-Netvisor-Authentication-MAC'           => $this->getAuthenticationMac($url, $authenticationTimestamp, $authenticationTransactionId),
        ));

        // Attach XML to the request.
        $this->client->setRawData($xml, 'text/xml');

        try {
            $result = new Result($this->client->request('POST')->getBody());

            if(strstr($result->ResponseStatus->Status[0], self::RESPONSE_STATUS_FAILED)) {
                throw new Exception();
            }

            return $result;
        } catch(\Zend_Http_Client_Exception $e) {
            throw $e;
        } catch(\Exception $e) {
            throw $e;
        }/*
    }

    /**
     * Calculates MAC MD5-hash for headers.
     *
     * @param   string  $url
     * @param   string  $authenticationTimestamp
     * @param   string  $authenticationTransactionId
     * @return  string
     */
    private function getAuthenticationMac($url, $authenticationTimestamp, $authenticationTransactionId)
    {
        $parameters = array(
            $url,
            $this->config->getSender(),
            $this->config->getCustomerId(),
            $authenticationTimestamp,
            $this->config->getLanguage(),
            $this->config->getOrganizationId(),
            $authenticationTransactionId,
            $this->config->getUserKey(),
            $this->config->getPartnerKey(),
        );

        return md5(implode('&', $parameters));
    }

    /**
     * Generates unique transaction id.
     *
     * @return string
     */
    private function getAuthenticationTransactionId()
    {
        return rand(1000, 9999) . microtime();
    }

    /**
     * Returns the current timestamp with 3-digit micro time.
     *
     * @return string
     */
    private function getAuthenticationTimestamp()
    {
        $timestamp = \DateTime::createFromFormat('U.u', microtime(true));
        $timestamp->setTimezone(new \DateTimeZone('GMT'));

        return substr($timestamp->format('Y-m-d H:i:s.u'), 0, -3);
    }
}