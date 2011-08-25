<?php

namespace Xi\Netvisor\Zend;

use Xi\Netvisor\Zend\Validate;
/**
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 */
class XmlifyInvoiceData 
{
   
    private $invoiceData;
    private $validationRules;
    private $validationErrors = array();
    private $xml;
    
    /**
     * Just initialize rules
     */
    public function __construct()
    {
        $this->initValidationRules();
    }
    
    /**
     * set Data 
     * 
     * @param array $invoiceData
     * @return XmlifyInvoiceData 
     */
    public function setInvoiceData($invoiceData)
    {
        $this->invoiceData = $invoiceData;  
        return $this;
    } 
    
    /**
     * creates new Xml string or trhows an exception if data is not valid
     * 
     * @return string xml
     * @exception Exception\XmlValidationException
     */
    public function createXml()
    {
        if($this->validate($this->invoiceData)){
            $this->xml = $this->writeToXml();
        } else {
            throw new Exception\XmlValidationException($this->validationErrors);
        }       
        return $this->xml;
    }

    /**
     * writes data to xml format.
     * 
     * @return string (xml) 
     */
    public function writeToXml()
    {
        $writer = new XMLWriter();
        $writer ->openMemory();
        //$writer->startDocument('1.0', 'UTF-8');
        $writer->setIndent(4); 
        $writer->startElement('Root');
            $writer->startElement('SalesInvoice');
            
                $writer->writeAttributeElement('SalesInvoiceNumber', $this->invoiceData);
                $writer->writeAttributeElement('SalesInvoiceDate', $this->invoiceData, array('format'=>'ansi'));         
                $writer->writeAttributeElement('SalesInvoiceDeliveryDate', $this->invoiceData, array('format'=>'ansi'));   
                $writer->writeAttributeElement('SalesInvoiceReferenceNumber', $this->invoiceData);  
                $writer->writeAttributeElement('SalesInvoiceAmount', $this->invoiceData);                

                $writer->writeAttributeElement('SellerIdentifier', $this->invoiceData, array('type'=>'netvisor'));             
                $writer->writeAttributeElement('SellerName', $this->invoiceData);                
                $writer->writeAttributeElement('SalesInvoiceStatus', $this->invoiceData, array('type'=>'netvisor'));  

                $writer->writeAttributeElement('SalesInvoiceFreeTextBeforeLines', $this->invoiceData); 
                $writer->writeAttributeElement('SalesInvoiceFreeTextAfterLines', $this->invoiceData); 
                $writer->writeAttributeElement('SalesInvoiceOurReference', $this->invoiceData); 
                $writer->writeAttributeElement('SalesInvoiceYourReference', $this->invoiceData); 
                $writer->writeAttributeElement('SalesInvoicePrivateComment', $this->invoiceData); 
                           
                $writer->writeAttributeElement('InvoicingCustomerIdentifier',$this->invoiceData, array('type' => ($this->invoiceData['InvoicingCustomerIdentifierType']?:'')));
                $writer->writeAttributeElement('InvoicingCustomerName', $this->invoiceData);                  
                $writer->writeAttributeElement('InvoicingCustomerNameExtension', $this->invoiceData);
                $writer->writeAttributeElement('InvoicingCustomerAddressLine', $this->invoiceData); 
                $writer->writeAttributeElement('InvoicingCustomerPostNumber', $this->invoiceData);
                $writer->writeAttributeElement('InvoicingCustomerTown', $this->invoiceData);
                $writer->writeAttributeElement('InvoicingCustomerCountryCode', $this->invoiceData, array('type' => 'ISO-3166'));
              
                $writer->writeAttributeElement('DeliveryAddressName', $this->invoiceData);
                $writer->writeAttributeElement('DeliveryAddressNameExtension', $this->invoiceData);
                $writer->writeAttributeElement('DeliveryAddressLine', $this->invoiceData);
                $writer->writeAttributeElement('DeliveryAddressPostNumber', $this->invoiceData);
                $writer->writeAttributeElement('DeliveryAddressTown', $this->invoiceData);               
                
                $writer->writeAttributeElement('DeliveryAddressCountryCode', $this->invoiceData, array('type' => 'ISO-3166'));
                $writer->writeAttributeElement('DeliveryMethod', $this->invoiceData);
                $writer->writeAttributeElement('DeliveryTerm', $this->invoiceData);
                $writer->writeAttributeElement('PaymentTermNetDays', $this->invoiceData);
                $writer->writeAttributeElement('PaymentTermCashDiscountDays', $this->invoiceData);                
                $writer->writeAttributeElement('PaymentTermCashDiscount', $this->invoiceData, array('type' => 'percentage'));
                $writer->writeAttributeElement('ExpectPartialPayments', $this->invoiceData);                
                $writer->writeAttributeElement('TryDirectDebitLink', $this->invoiceData, array('mode' => $this->invoiceData['TryDirectDebitLinkMode']));
        
                $writer->startElement('InvoiceLines');
                
                    foreach($this->invoiceData['InvoiceLines'] as $invoiceLine) {
                        if(isset($invoiceLine['ProductIdentifier'])) {
                            $writer->startElement('InvoiceLine');  
                                $writer->startElement('SalesInvoiceProductLine');

                                    $writer->writeAttributeElement('ProductIdentifier',$invoiceLine,array('type' => $invoiceLine['ProductIdentifierType']));
                                    $writer->writeAttributeElement('ProductName', $invoiceLine);
                                    $writer->writeAttributeElement('ProductUnitPrice', $invoiceLine, array('type' => 'net'));    
                                    $writer->writeAttributeElement('ProductVatPercentage', $invoiceLine, array('vatcode' => ($invoiceLine['ProductVatPercentageVatCode']?:'')));
                                    $writer->writeAttributeElement('SalesInvoiceProductLineQuantity', $invoiceLine);
                                    $writer->writeAttributeElement('SalesInvoiceProductLineDiscountPercentage', $invoiceLine);
                                    $writer->writeAttributeElement('AccountingAccountSuggestion', $invoiceLine);

                                    if(!empty($incoiceLine['Dimensions'])) {
                                        foreach($invoiceLine['Dimensions'] as $dimension) {
                                            $writer->startElement('Dimension');
                                            $writer->writeAttributeElement('DimensionName', $dimension);
                                            $writer->writeAttributeElement('DimensionItem', $dimension);
                                            $writer->endElement(); // dimension
                                        }
                                    }

                                // SalesInvoiceProductLine
                                $writer->endElement();                        
                            // InvoiceLine
                            $writer->endElement(); 
                        } else if(isset($invoiceLine['Comment'])) {
                            $writer->startElement('InvoiceLine');  
                                $writer->startElement('SalesInvoiceCommentLine');

                                    $writer->writeAttributeElement('Comment', $invoiceLine);
                                    
                                // SalesInvoiceCommentLine
                                $writer->endElement();                        
                            // InvoiceLine
                            $writer->endElement(); 
                        }
                        
                        /*if(isset($this->invoiceData['Comment'])){
                            $writer->startElement('InvoiceLine');  
                                $writer->startElement('SalesInvoiceCommentLine'); 

                                    $writer->writeAttributeElement('Comment', $this->invoiceData['Comment']);    

                                // SalesInvoiceProductLine
                                $writer->endElement();                        
                            // InvoiceLine
                            $writer->endElement();
                        }*/
                    }
                // InvoiceLines
                $writer->endElement(); 
                
                if(!empty($this->invoiceData['InvoiceVoucherLines'])) {
                    $writer->startElement('InvoiceVoucherLines');

                    foreach($this->invoiceData['InvoiceVoucherLines'] as $voucherLine) {
                        $writer->startElement('VoucherLine');

                            $writer->writeAttributeElement('LineSum',$voucherLine, array('type' => 'net'));
                            $writer->writeAttributeElement('Description', $voucherLine);
                            $writer->writeAttributeElement('AccountNumber', $voucherLine);
                            $writer->writeAttributeElement('VatPercent',$voucherLine, array('vatcode' => ($voucherLine['VatCode']?:'')));

                         // VoucherLine
                        $writer->endElement();
                    }            
                    // InvoiceVoucherLines
                    $writer->endElement();
                }
                
                if(!empty($this->invoiceData['‹'])) {
                    $writer->startElement('SalesInvoiceAttachments');

                        foreach($this->invoiceData['SalesInvoiceAttachments'] as $salesInvoiceAttachment) {

                            $writer->startElement('SalesInvoiceAttachment');

                                $writer->writeAttributeElement('MimeType', $salesInvoiceAttachment);
                                $writer->writeAttributeElement('AttachmentDescription', $salesInvoiceAttachment);
                                $writer->writeAttributeElement('FileName', $salesInvoiceAttachment);

                                $writer->startElement('DocumentData');

                                    $writer->text(base64_encode($salesInvoiceAttachment['DocumentData']));
                                // DocumentData
                                $writer->endElement();
                            // SalesInvoiceAttachment
                            $writer->endElement();    
                        }
                    // SalesInvoiceAttachments
                    $writer->endElement();
                }
                
                if(!empty($this->invoiceData['CustomTags'])) {
                    $writer->startElement('CustomTags');

                        foreach($this->invoiceData['CustomTags'] as $customTag)
                        {
                            $writer->startElement('Tag');  
                                $writer->writeAttributeElement('TagName', $customTag);
                                $writer->writeAttributeElement('TagValue', $customTag, array('datatype' => ($customTag['dataType']?:'')));                   
                            // Tag
                            $writer->endElement();
                        }

                    // CustomTags
                    $writer->endElement();
                }
                
            // SalesInvoice
            $writer->endElement();
              
        // End Root
        $writer->endElement();
        
        //echo $writer->outputMemory(TRUE);
        //die();
       
        // $writer->endDocument();
        return $writer->outputMemory(TRUE);
        
    }

    /**
     * validates data
     * 
     * @param array $invoiceData
     * @param array $required
     * @return bool 
     */
    private function validate($invoiceData, $required = false)
    {
        $filterInput = new \Zend_Filter_Input(null, $this->validationRules);
     
        foreach($invoiceData as $key => $value)
        { 
            if(is_array($value)){
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

        if(empty($this->validationErrors)){
            return true;
        }
        return false;
    }
    

    /**
     * initialize validation rules
     */
    private function initValidationRules()
    {
        $vatcode = array('NONE','KOOS','EUOS','EUUO','EUPO','100','KOMY','EUMY','EUUM','EUPM312','EUPM309','MUUL','EVTO','EVPO','RAMY','RAOS');
        
        $this->validationRules = array(
            'SalesInvoiceNumber'                        => array('Alnum'), 
            'SalesInvoiceDate'                          => array(new Validate\AnsiDate, 'NotEmpty'), 
            'SalesInvoiceDeliveryDate'                  => array(new Validate\AnsiDate),
            'SalesInvoiceReferenceNumber'               => array(new Validate\TransactionReferenceNumber()),
            'SalesInvoiceAmount'                        => array('Float', 'NotEmpty'),
            'SellerIdentifier'                          => array('Alnum'), // float
            'SellerName'                                => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 50)),
            'SalesInvoiceStatus'                        => array('NotEmpty', array('InArray', 'haystack' => array('open', 'unsent'))),
            'SalesInvoiceFreeTextBeforeLines'           => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 500)),
            'SalesInvoiceFreeTextAfterLines'            => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 500)),
            'SalesInvoiceOurReference'                  => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 200)),
            'SalesInvoiceYourReference'                 => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 200)),
            'SalesInvoicePrivateComment'                => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 255)),
            
            'InvoicingCustomerIdentifier'               => array('Alnum', 'NotEmpty'),
            'InvoicingCustomerIdentifierType'           => array('NotEmpty', array('InArray', 'haystack' => array('customer', 'netvisor'))),
            'InvoicingCustomerName'                     => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 250)),
            'InvoicingCustomerNameExtension'            => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 250)),
            'InvoicingCustomerAddressLine'              => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 100)),
            'InvoicingCustomerPostNumber'               => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 50)),
            'InvoicingCustomerTown'                     => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 50)),
            'InvoicingCustomerCountryCode'              => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 2)),  // ISO-3166
            
            'DeliveryAddressName'                       => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 250)),
            'DeliveryAddressNameExtension'              => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 250)),
            'DeliveryAddressLine'                       => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 100)),
            'DeliveryAddressPostNumber'                 => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 50)),
            'DeliveryAddressTown'                       => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 50)),
            'DeliveryAddressCountryCode'                => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 2)),  // ISO-3166

            'DeliveryMethod'                            => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 100)),
            'DeliveryTerm'                              => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 100)),
            'PaymentTermNetDays'                        => array('Digits', 'NotEmpty'),
            'PaymentTermCashDiscountDays'               => array('Digits'),
            'PaymentTermCashDiscount'                   => array('Float'),
            'ExpectPartialPayments'                     => array( array('InArray', 'haystack' => array(0,1))),
            'TryDirectDebitLink'                        => array( array('InArray', 'haystack' => array(0,1))),
            'TryDirectDebitLinkMode'                    => array('NotEmpty', array('InArray', 'haystack' => array('fail_on_error', 'ignore_error'))),

            //InvoiceLines
            'ProductIdentifier'                         => array('Alnum', 'NotEmpty'),
            'ProductIdentifierType'                     => array('NotEmpty', array('InArray', 'haystack' => array('customer', 'netvisor'))),
            'ProductName'                               => array('Alnum'=> array('allowWhiteSpace' => true), 'NotEmpty',array('StringLength', 0, 50)),
            'ProductUnitPrice'                          => array('Float', 'NotEmpty'),
            'ProductUnitPriceType'                      => array('NotEmpty', array('InArray', 'haystack' => array('net', 'gross'))),
            'ProductVatPercentage'                      => array('Float', 'NotEmpty'),
            'ProductVatPercentageVatCode'               => array('NotEmpty', array('InArray', 'haystack' => $vatcode)),
            //'SalesInvoiceProductLineQuantity'           => array('Float', 'NotEmpty'),
            //'SalesInvoiceProductLineDiscountPercentage' => array('Float'),
            //'SalesInvoiceProductLineFreeText'           => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 200)),
            //'SalesInvoiceProductLineSum'                => array('Float'),
            //'SalesInvoiceProductLineVatSum'             => array('Float'),
            'AccountingAccountSuggestion'               => array('Float'),
            
            'DimensionName'                             => array('Alnum'=> array('allowWhiteSpace' => true), 'NotEmpty',array('StringLength', 0, 50)),
            'DimensionItem'                             => array('Alnum'=> array('allowWhiteSpace' => true), 'NotEmpty',array('StringLength', 0, 200)),
            
            'LineSum'                                   => array('Float', 'NotEmpty'),
            'LineSumType'                               => array('NotEmpty', array('InArray', 'haystack' => array('net', 'gross'))),
            'Description'                               => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 255)),
            'AccountNumber'                             => array('Int', 'NotEmpty'),
            'VatPercent'                                => array('Float', 'NotEmpty'),
            'VatCode'                                   => array('NotEmpty', array('InArray', 'haystack' => $vatcode)),       
            
            'MimeType'                                  => array('Alnum', 'NotEmpty'),
            'AttachmentDescription'                     => array('Alnum'=> array('allowWhiteSpace' => true), 'NotEmpty',array('StringLength', 0, 100)),
            'FileName'                                  => array('Alnum'=> array('allowWhiteSpace' => true), 'NotEmpty',array('StringLength', 0, 255)),
            'DocumentData'                              => array('Alnum', 'NotEmpty'),

            'TagName'                                   => array('Alnum', 'NotEmpty'),
            'TagValue'                                  => array('Alnum'=> array('allowWhiteSpace' => true), 'NotEmpty'),
            'TagValueDataType'                          => array('NotEmpty', array('InArray', 'haystack' => array('enum', 'date', 'float', 'text'))),         
        );
    }    
}
