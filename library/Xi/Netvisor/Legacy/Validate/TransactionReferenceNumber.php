<?php
namespace Xi\Netvisor\Zend\Validate;

/**
 * Validate transaction reference number
 *
 * @category Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author   Henri Vesala <henri.vesala@gmail.com>
 */
class TransactionReferenceNumber extends \Zend_Validate_Abstract
{
    const NOTNUMERIC        = 'transactionReferenceNumberNotNumeric';
    const TOOLONG           = 'transactionReferenceNumberTooLong';
    const INVALIDCHECKSUM   = 'transactionReferenceNumberChecksumm';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOTNUMERIC => "'%value%' is not valid numeric transaction reference number.",  
        self::TOOLONG => "'%value%' is not valid transaction reference number. Number is too long.",
        self::INVALIDCHECKSUM  => "'%value%' is not valid transaction reference number. Invalid checksum.",
    );

    /**
     * validates transaction reference number
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if(!is_numeric($value)){
            $this->_error(self::NOTNUMERIC);
            return false;   
        }

        $checkDigit = $value % 10;
        $value = $value.'';         // as string
        
        if(strlen($value) > 20) {
            $this->_error(self::TOOLONG);
            return false;
        }
        
        $multipliers  = array('7','3','1');
            

        
        $sum = 0;
        for($i = 0; $i < strlen($value) -1; $i++) {
            $sum += $value[$i]*$multipliers[$i%3];
        }

        $calcCheckDigit = $sum % 10;
        if($calcCheckDigit){
            $calcCheckDigit = 10 - $calcCheckDigit;
        } 

        if($checkDigit != $calcCheckDigit){
            $this->_error(self::INVALIDCHECKSUM);
            return false;      
        } 
        return true;

    }
}
