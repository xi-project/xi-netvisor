<?php
namespace Xi\Netvisor\Zend;

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
 */
class Netvisor extends \Zend_Rest_Client
{   
    const SERVICE_INVOICE_SALES = 'salesinvoice';
    
    const RESPONSE_STATUS_OK     = 'OK',
          RESPONSE_STATUS_FAILED = 'FAILED';
    
    /**
     * @var Zend_Http_Client
     */
    private $client = null;
    
    /**
     * @var Zend_Config
     */
    private $config = null; // @todo refactor to not use registry/config
    
    
    public function __construct()
    {
        $this->config = $this->getConfig();        
        $this->client = new \Zend_Http_Client();
        $this->client->setConfig(array('maxdirects' => 0, 'timeout' => 30, 'keepalive' => true));
    }
    
    /**
     * Sends an invoice to Netvisor.
     * 
     * @param   string  $xml
     * @return  Zend_Rest_Client_Result 
     */
    public function invoice($xml)
    {
        return $this->request($xml, self::SERVICE_INVOICE_SALES);
    }
    
    /**
     * Makes a request to Netvisor and returns a response.
     * 
     * @param   string  $xml
     * @param   string  $service
     * @return  Zend_Rest_Client_Result 
     */
    private function request($xml, $service)
    {
        if(!$this->config->interface->enabled) {
            return null;
        }
        
        $url = "{$this->config->interface->host}/{$service}.nv";

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
            'X-Netvisor-Authentication-MAC'           => $this->getAuthenticationMac($service, $authenticationTimestamp, $authenticationTransactionId),
            
        ));

        // Attach XML to the request.
        $this->client->setRawData($xml, 'text/xml');
        
        try {
            $result = new \Zend_Rest_Client_Result($this->client->request('POST')->getBody());
            
            if(strstr($result->ResponseStatus->Status[0], self::RESPONSE_STATUS_FAILED)) {
                throw new Exception\NetvisorResponseStatusException($result->ResponseStatus->Status[1]);
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
     * @param   string  $service
     * @param   string  $authenticationTimestamp
     * @param   string  $authenticationTransactionId
     * @return  string 
     */
    private function getAuthenticationMac($service, $authenticationTimestamp, $authenticationTransactionId)
    {
        $parameters = array(
            "{$this->config->interface->host}/{$service}.nv",
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
    
    /**
     * @return Zend_Config
     */
    private function getConfig()
    {
        return \Zend_Registry::get('config')->netvisor;
    }

}
