<?php

namespace Xi\Netvisor\Zend;

use Xi\Netvisor\Zend\Validate;
/**
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Panu Leppäniemi    <me@panuleppaniemi.com>
 */
class XmlifyCustomer extends Xmlify
{
    
    /**
     * Writes data to XML format.
     * 
     * @return string xml
     */
    public function writeToXml()
    {
        $writer = new XmlWriter();
        $writer ->openMemory();
        //$writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(4); 
        $writer->startElement('Root');
            $writer->startElement('Customer');
                $writer->startElement('CustomerBaseInformation');
                    
                    $writer->writeAttributeElement('InternalIdentifier', $this->data);
                    $writer->writeAttributeElement('ExternalIdentifier', $this->data);
                    
                    $writer->writeAttributeElement('Name', $this->data);
                    $writer->writeAttributeElement('NameExtension', $this->data);
                    
                    $writer->writeAttributeElement('StreetAddress', $this->data);
                    $writer->writeAttributeElement('City',          $this->data);
                    $writer->writeAttributeElement('PostNumber',    $this->data);                    
                    $writer->writeAttributeElement('Country',       $this->data, array('type' => 'ISO-3166'));
                    
                    $writer->writeAttributeElement('PhoneNumber', $this->data);
                    $writer->writeAttributeElement('FaxNumber',   $this->data);
                    $writer->writeAttributeElement('Email',       $this->data);
                    
                $writer->endElement();
                
                if(isset($this->data['Delivery']) && $this->data['Delivery']) {
                    $writer->startElement('CustomerDeliveryDetails');

                        $writer->writeAttributeElement('DeliveryStreetAddress', $this->data['Delivery'][0]);
                        $writer->writeAttributeElement('DeliveryCity',          $this->data['Delivery'][0]);
                        $writer->writeAttributeElement('DeliveryPostNumber',    $this->data['Delivery'][0]);                    
                        $writer->writeAttributeElement('DeliveryCountry',       $this->data['Delivery'][0], array('type' => 'ISO-3166'));

                    $writer->endElement();
                }
                
                if(isset($this->data['Finvoice']) && $this->data['Finvoice']) {
                    $writer->startElement('CustomerFinvoiceDetails');

                        $writer->writeAttributeElement('FinvoiceAddress',    $this->data['Finvoice'][0]);
                        $writer->writeAttributeElement('FinvoiceRouterCode', $this->data['Finvoice'][0]);

                    $writer->endElement();
                }
                
                if(isset($this->data['ContactPerson']) && $this->data['ContactPerson']) {
                    $writer->startElement('CustomerContactDetails');

                        $writer->writeAttributeElement('ContactPerson',      $this->data['ContactPerson'][0]);
                        $writer->writeAttributeElement('ContactPersonEmail', $this->data['ContactPerson'][0]);
                        $writer->writeAttributeElement('ContactPersonPhone', $this->data['ContactPerson'][0]);

                    $writer->endElement();
                }
                
            $writer->endElement();
        $writer->endElement();

        return $writer->outputMemory(TRUE);
    }
    
    /**
     * Initialize validation rules.
     * 
     * @return XmlifyInvoiceData
     */
    protected function initValidationRules()
    {
        $vatcode = array('NONE','KOOS','EUOS','EUUO','EUPO','100','KOMY','EUMY','EUUM','EUPM312','EUPM309','MUUL','EVTO','EVPO','RAMY','RAOS');
        
        $this->validationRules = array(
            'InternalIdentifier'    => array('NotEmpty'),
            'ExternalIdentifier'    => array('NotEmpty'),
            
            'Name' => array('NotEmpty'),
        );
        
        return $this;
    }    
}
