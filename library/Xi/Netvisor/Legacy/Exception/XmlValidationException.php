<?php
namespace Xi\Netvisor\Zend\Exception;

/**
 *  Xml validation exception for invalid xml. 
 *  automatically creates error message validation error array
 * 
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 */
class XmlValidationException extends \Zend_Exception
{
    private $xmlValidationErrors;
   
    /**
     * creates new validation error exception
     * 
     * @param array $validationErrors 
     */
    public function __construct($validationErrors) {
        $this->xmlValidationErrors = $validationErrors;    
        parent::__construct($this->createErrorMessage($validationErrors));
    }
    
    /**
     * creates error message from validation error array
     * 
     * @param array $validationErrors
     * @return string 
     */
    private function createErrorMessage($validationErrors)
    {
        $errorMessage = "Xml Validation errors: \n";
        foreach($validationErrors as $errorline) {
            foreach($errorline as $errorName => $errors) {
                $errorMessage.= "$errorName: ".implode(', ',$errors)."\n";
            }
        }
        return $errorMessage;
    }
}