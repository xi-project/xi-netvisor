<?php
namespace Xi\Netvisor\Zend\Exception;

/**
 *  Netvisor response status exception
 * 
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 */
class NetvisorResponseStatusException extends \Zend_Service_Exception
{
    /**
     * @var string
     */
    protected $request;

    /**
     * @var string
     */
    protected $response;

    /**
     * Populate the request and response from given client.
     *
     * @param \Zend_Http_Client $client
     */
    public function populateFromClient(\Zend_Http_Client $client)
    {
        $this->request = $client->getLastRequest();
        $this->response = $client->getLastResponse();
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Zend_Http_Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
