<?php

namespace Xi\Netvisor\Zend;

use Xi\Netvisor\Zend\Validate;
/**
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 * @author     Panu Leppäniemi  <me@panuleppaniemi.com>
 */
abstract class Xmlify
{
   
    protected $data;
    protected $validationRules;
    protected $validationErrors = array();
    protected $xml;
    
    /**
     * Just initialize rules.
     */
    public function __construct()
    {
        $this->initValidationRules();
    }
    
    /**
     * Writes data to XML format.
     * 
     * @return string xml
     */
    abstract public function writeToXml();
    
    /**
     * Initialize validation rules.
     * 
     * @return Xmlify
     */
    abstract protected function initValidationRules();
    
    /**
     * Sets the data array.
     * The given array will be formatted to XML
     * when createXml() is called.
     * 
     * @param array $data
     * @return Xmlify 
     */
    public function setData($data)
    {
        $this->data = $data;
        
        return $this;
    } 
    
    /**
     * Creates new XML string or throws an exception if data is not valid.
     * 
     * @return string xml
     * @exception Exception\XmlValidationException
     */
    public function createXml()
    {
        if($this->validate($this->data)) {
            return $this->xml = $this->writeToXml();
        } else {
            throw new Exception\XmlValidationException($this->validationErrors);
        }
    }    
    
    /**
     * Validates data using validation rules.
     * 
     * @param array $data
     * @param array $required
     * @return bool 
     */
    protected function validate($invoiceData, $required = false)
    {
        $filterInput = new \Zend_Filter_Input(null, $this->validationRules);
     
        foreach($invoiceData as $key => $value) { 
            if(is_array($value)) {
                foreach($value as $rows) {
                    $this->validate($rows, true);
                }                    
            } else if(isset($this->validationRules[$key])) {                
               $filterInput->setData(array($key => $value));
               
               if(!$filterInput->isValid()){   
                   $this->validationErrors[] = $filterInput->getErrors();
               }
            }            
        }

        if(empty($this->validationErrors)) {
            return true;
        }
        
        return false;
    }
}
