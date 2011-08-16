<?php
namespace Xi\Netvisor\Zend\Validate;
/**
 * Validate ANSI Date format
 *

 * @category Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author   Henri Vesala   <henri.vesala@gmail.com>
 */
class AnsiDate extends \Zend_Validate_Abstract
{
    const INVALID_DATE = 'dateInvalidDate';
    const FALSEFORMAT  = 'dateFalseFormat';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_DATE => "'%value%' does not appear to be a valid date",
        self::FALSEFORMAT  => "'%value%' does not fit the ANSI date format 'yyyy-mm-dd'",
    );

    /**
     * Check if $value is a valid date and valid ANSI format (yyyy-mm-dd)
     *
     * @param  string $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        if (!preg_match('#^\d{4}-\d{2}-\d{2}$#', $value)) {
            $this->_error(self::FALSEFORMAT);

            return false;
        }

        list($year, $month, $day) = sscanf($value, '%d-%d-%d');

        if (!checkdate($month, $day, $year)) {
            $this->_error(self::INVALID_DATE);

            return false;
        }

        return true;
    }
}