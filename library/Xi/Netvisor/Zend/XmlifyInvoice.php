<?php

namespace Xi\Netvisor\Zend;

use Xi\Netvisor\Zend\Validate;
/**
 * @category   Xi
 * @package    Netvisor
 * @subpackage Zend
 * @author     Henri Vesala     <henri.vesala@gmail.fi>
 */
class XmlifyInvoice extends Xmlify
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
            $writer->startElement('SalesInvoice');
            
                $writer->writeAttributeElement('SalesInvoiceNumber', $this->data);
                $writer->writeAttributeElement('SalesInvoiceDate', $this->data, array('format'=>'ansi'));         
                $writer->writeAttributeElement('SalesInvoiceDeliveryDate', $this->data, array('format'=>'ansi'));   
                $writer->writeAttributeElement('SalesInvoiceReferenceNumber', $this->data);  
                $writer->writeAttributeElement('SalesInvoiceAmount', $this->data);                

                $writer->writeAttributeElement('SellerIdentifier', $this->data, array('type'=>'netvisor'));             
                $writer->writeAttributeElement('SellerName', $this->data);                
                $writer->writeAttributeElement('SalesInvoiceStatus', $this->data, array('type'=>'netvisor'));  

                $writer->writeAttributeElement('SalesInvoiceFreeTextBeforeLines', $this->data); 
                $writer->writeAttributeElement('SalesInvoiceFreeTextAfterLines', $this->data); 
                $writer->writeAttributeElement('SalesInvoiceOurReference', $this->data); 
                $writer->writeAttributeElement('SalesInvoiceYourReference', $this->data); 
                $writer->writeAttributeElement('SalesInvoicePrivateComment', $this->data); 
                           
                $writer->writeAttributeElement('InvoicingCustomerIdentifier',$this->data, array('type' => ($this->data['InvoicingCustomerIdentifierType']?:'')));
                $writer->writeAttributeElement('InvoicingCustomerName', $this->data);                  
                $writer->writeAttributeElement('InvoicingCustomerNameExtension', $this->data);
                $writer->writeAttributeElement('InvoicingCustomerAddressLine', $this->data); 
                $writer->writeAttributeElement('InvoicingCustomerPostNumber', $this->data);
                $writer->writeAttributeElement('InvoicingCustomerTown', $this->data);
                $writer->writeAttributeElement('InvoicingCustomerCountryCode', $this->data, array('type' => 'ISO-3166'));
              
                $writer->writeAttributeElement('DeliveryAddressName', $this->data);
                $writer->writeAttributeElement('DeliveryAddressNameExtension', $this->data);
                $writer->writeAttributeElement('DeliveryAddressLine', $this->data);
                $writer->writeAttributeElement('DeliveryAddressPostNumber', $this->data);
                $writer->writeAttributeElement('DeliveryAddressTown', $this->data);               
                $writer->writeAttributeElement('DeliveryAddressCountryCode', $this->data, array('type' => 'ISO-3166'));
                
                $writer->writeAttributeElement('DeliveryMethod', $this->data);
                $writer->writeAttributeElement('DeliveryTerm', $this->data);
                $writer->writeAttributeElement('PaymentTermNetDays', $this->data);
                $writer->writeAttributeElement('PaymentTermCashDiscountDays', $this->data);                
                $writer->writeAttributeElement('PaymentTermCashDiscount', $this->data, array('type' => 'percentage'));
                $writer->writeAttributeElement('ExpectPartialPayments', $this->data);                
                $writer->writeAttributeElement('TryDirectDebitLink', $this->data, array('mode' => $this->data['TryDirectDebitLinkMode']));
        
                $writer->startElement('InvoiceLines');
                
                    foreach($this->data['InvoiceLines'] as $invoiceLine) {
                        if(isset($invoiceLine['ProductIdentifier'])) {
                            $writer->startElement('InvoiceLine');  
                                $writer->startElement('SalesInvoiceProductLine');

                                    $writer->writeAttributeElement('ProductIdentifier',$invoiceLine,array('type' => $invoiceLine['ProductIdentifierType']));
                                    $writer->writeAttributeElement('ProductName', $invoiceLine);
                                    $writer->writeAttributeElement('ProductUnitPrice', $invoiceLine, array('type' => 'net'));    
                                    $writer->writeAttributeElement('ProductVatPercentage', $invoiceLine, array('vatcode' => ($invoiceLine['ProductVatPercentageVatCode']?:'')));
                                    $writer->writeAttributeElement('SalesInvoiceProductLineQuantity', $invoiceLine);
                                    $writer->writeAttributeElement('SalesInvoiceProductLineFreeText', $invoiceLine);
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
                    }
                // InvoiceLines
                $writer->endElement(); 
                
                if(!empty($this->data['InvoiceVoucherLines'])) {
                    $writer->startElement('InvoiceVoucherLines');

                    foreach($this->data['InvoiceVoucherLines'] as $voucherLine) {
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
                
                if(!empty($this->data['‹'])) {
                    $writer->startElement('SalesInvoiceAttachments');

                        foreach($this->data['SalesInvoiceAttachments'] as $salesInvoiceAttachment) {

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
                
                if(!empty($this->data['CustomTags'])) {
                    $writer->startElement('CustomTags');

                        foreach($this->data['CustomTags'] as $customTag)
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
            'SalesInvoiceNumber'                        => array('Alnum'), 
            'SalesInvoiceDate'                          => array(new Validate\AnsiDate, 'NotEmpty'), 
            'SalesInvoiceDeliveryDate'                  => array(new Validate\AnsiDate),
            'SalesInvoiceReferenceNumber'               => array(new Validate\TransactionReferenceNumber()),
            'SalesInvoiceAmount'                        => array('Float', 'NotEmpty'),
            'SellerIdentifier'                          => array('Alnum'),
            'SellerName'                                => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 50)),
            'SalesInvoiceStatus'                        => array('NotEmpty', array('InArray', 'haystack' => array('open', 'unsent'))),
            'SalesInvoiceFreeTextBeforeLines'           => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 500)),
            'SalesInvoiceFreeTextAfterLines'            => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 500)),
            'SalesInvoiceOurReference'                  => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 200)),
            'SalesInvoiceYourReference'                 => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 200)),
            'SalesInvoicePrivateComment'                => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 255)),
            
            'InvoicingCustomerIdentifier'               => array('NotEmpty'),
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
            'SalesInvoiceProductLineQuantity'           => array('Float', 'NotEmpty'),
            //'SalesInvoiceProductLineDiscountPercentage' => array('Float'),
            'SalesInvoiceProductLineFreeText'           => array('Alnum'=> array('allowWhiteSpace' => true), array('StringLength', 0, 200)),
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
        
        return $this;
    }    
}
