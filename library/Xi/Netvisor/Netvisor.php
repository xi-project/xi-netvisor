<?php
namespace Xi\Netvisor\Zend;

use Zend_Http_Client as Client,
    Zend_Rest_Client_Result as Result;

/**
 * 
 * Connects to Netvisor-interface via HTTP.
 * Authentication is based on HTTP headers.
 * A single XML file is sent to the server.
 * The server returns a XML response that contains the transaction status.
 * 
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Panu Leppäniemi  <me@panuleppaniemi.com>
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 * @author     Petri Koivula    <petri.koivula@iki.fi>
 */
class Netvisor
{
    const METHOD_ADD  = 'add',
          METHOD_EDIT = 'edit';
    
    const RESPONSE_STATUS_OK     = 'OK',
          RESPONSE_STATUS_FAILED = 'FAILED';

    private $config = null;

    public function __construct($config)
    {
    }

    /**
     * Adds an invoice to Netvisor.
     *
     * @param  string $xml
     * @return Result
     */
    public function invoiceAdd($xml)
    {
        return $this->request($xml, self::SERVICE_INVOICE_ADD);
    }

    /**
     * Get a list of invoices at Netvisor.
     *
     * @return Result
     */
    public function invoiceList()
    {
        return $this->request('', self::SERVICE_INVOICE_LIST);
    }

    /**
     * Makes a request to Netvisor and returns a response.
     * 
     * @param  string $xml
     * @param  string $service
     * @return Result
     */
    private function request($xml, $service, $method = null, $id = null)
    {
        if(!$this->config->interface->enabled) {
            return null;
        }
        
        $url = "{$this->config->interface->host}/{$service}.nv";

        $params = array(
            'method' => $method,
            'id' => $id,
        );
        $params = array_filter($params);
        $queryString = http_build_query($params);
        if ($queryString) {
            $url .= '?' . $queryString;
        }

        // Reset the client.
        $this->client->resetParameters(true);
        
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
        }
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
            $this->config->interface->sender,
            $this->config->interface->customerId,
            $authenticationTimestamp,
            $this->config->interface->language,
            $this->config->interface->organizationId,        
            $authenticationTransactionId,
            $this->config->interface->userKey,
            $this->config->interface->partnerKey,
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
        return rand(1000,9999) . microtime();
    }
    
    /**
     * Returns the current timestamp with 3-digit microtime.
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
