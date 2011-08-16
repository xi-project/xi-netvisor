<?php

namespace Xi\Netvisor\Zend;

/**
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 */
class XmlWriter extends \XMLWriter
{

    /**
     *  Quicker way to write xml line.
     * 
     *
     * @param string $name
     * @param array $contentArray
     * @param array $attributes 
     */
    public function writeAttributeElement($name, $contentArray, $attributes = null)
    {
        if(isset($contentArray[$name])){
            $this->startElement($name);
            
            if(!empty($attributes) && is_array($attributes)){
                
                foreach($attributes as $attrName => $attrValue){
                    if(isset($attrValue)){
                        $this->writeAttribute($attrName, $attrValue);
                    }
                }
            }    
            $this->text($contentArray[$name]);                 
            $this->endElement();
        }
    }

}