<?php
namespace Xi\Netvisor\Zend;

/**
 * 
 * connects to netvisor and sends them invoice xml.
 * 
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Panu Leppäniemi  <me@panuleppaniemi.com>
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 */
class Invoicing extends \Zend_Rest_Client
{
    const METHOD_ADD  = 'add',
          METHOD_EDIT = 'edit?id=';
    
    const SERVICE_INVOICE_SALES = 'salesinvoice';
    
    const RESPONSE_STATUS_OK     = 'OK',
          RESPONSE_STATUS_FAILED = 'FAILED';
    
    /**
     * @var Zend_Http_Client
     */
    private $client = null;
    
    /**
     * @var Zend_Http_Config
     */
    private $config = null;
    
    
    public function __construct()
    {
        $this->config = $this->_getConfig();
        
        $this->client = new \Zend_Http_Client(
            $this->config->interface->host,
            array('maxdirects' => 0, 'timeout' => 30, 'keepalive' => true)
        );
    }
    
    /**
     * Sends an invoice to Netvisor.
     * 
     * @param   string  $xml
     * @return  Zend_Rest_Client_Result 
     */
    public function addInvoice($xml)
    {
        return $this->_request($xml, self::SERVICE_INVOICE_SALES);
    }
    
    /**
     * Edits an invoice that already exists in Netvisor.
     * 
     * @param   string  $xml
     * @return  Zend_Rest_Client_Result 
     */
    public function editInvoice($id, $xml)
    {
        return $this->_request($xml, self::SERVICE_INVOICE_SALES, self::METHOD_EDIT . $id);
    }
    
    /**
     * Makes a request to Netvisor and returns a response.
     * 
     * @param   string  $xml
     * @param   string  $service
     * @param   string  $method
     * @return  Zend_Rest_Client_Result 
     */
    private function _request($xml, $service, $method = self::METHOD_ADD)
    {
        if(!$this->config->interface->enabled) {
            return null;
        }
        
        $timestamp = \DateTime::createFromFormat('U.u', microtime(true));
        $timestamp->setTimezone(new \DateTimeZone('GMT'));
        $time = substr($timestamp->format('Y-m-d H:i:s.u'),0,-3);
        

        $url = "{$this->config->interface->host}/{$service}.nv";  //?method={$method}

        // Reset the client.
        $this->client->resetParameters(true);
        
        // Start building the client.
        $this->client->setUri($url);
        
        $authenticationTransactionId = $this->getAuthenticationTransactionId();

      
        
        // Set headers which Netvisor demands.
        $this->client->setHeaders(array(
            'X-Netvisor-Authentication-Sender'          => $this->config->interface->sender,
            'X-Netvisor-Authentication-CustomerId'      => $this->config->interface->customerId,
            'X-Netvisor-Authentication-PartnerId'       => $this->config->interface->partnerId,
            'X-Netvisor-Authentication-Timestamp'       => $time,            
            'X-Netvisor-Interface-Language'             => $this->config->interface->language,
            'X-Netvisor-Organisation-ID'                => $this->config->interface->organizationId,
            'X-Netvisor-Authentication-TransactionId'   => $authenticationTransactionId,
            'X-Netvisor-Authentication-MAC'             => $this->_getAuthenticationMac($service, $time, $authenticationTransactionId),
            
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
     * @param   string      $service
     * @param   DateTime    $timestamp
     * @param   string      $authenticationTransactionId
     * @return  string 
     */
    private function _getAuthenticationMac($service, $time, $authenticationTransactionId)
    {
        $parameters = array(
            "{$this->config->interface->host}/{$service}.nv",
            $this->config->interface->sender,
            $this->config->interface->customerId,
            $time,
            $this->config->interface->language,
            $this->config->interface->organizationId,        
            $authenticationTransactionId,
            $this->config->interface->userKey,
            $this->config->interface->partnerKey,
        );
           
            
        return md5(implode('&', $parameters));
    }
    

    /**
     * generates unique transaction id
     * 
     * @return string
     */
    private function getAuthenticationTransactionId()
    {
        return rand(1000,9999).microtime();
    }
    
    
    /**
     * @return Zend_Config
     */
    private function _getConfig()
    {
        return \Zend_Registry::get('config')->netvisor;
    }

}
