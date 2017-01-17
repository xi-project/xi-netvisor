<?php

namespace Xi\Netvisor\Resource\Xml;

use JMS\Serializer\Annotation\XmlList;
use JMS\Serializer\Annotation\XmlKeyValuePairs;
use JMS\Serializer\Annotation\Inline;
use Xi\Netvisor\Resource\Xml\Component\Root;
use Xi\Netvisor\Resource\Xml\Component\AttributeElement;
use Xi\Netvisor\Resource\Xml\Component\WrapperElement;

/**
 * TODO: Should be kept immutable?
 */
class SalesInvoice extends Root
{
    // TODO Some of these will not work, as they need additional attributes
    const ALLOWED_ADDITIONAL_FIELDS = [
        'salesInvoiceNumber',
        'salesInvoiceDeliveryDate',
        'salesInvoiceReferenceNumber',
        'sellerName',
        'invoiceType',
        'salesInvoiceFreeTextBeforeLines',
        'salesInvoiceFreeTextAfterLines',
        'salesInvoiceOurReference',
        'salesInvoiceYourReference',
        'salesInvoicePrivateComment',
        'invoicingCustomerName',
        'invoicingCustomerNameExtension',
        'invoicingCustomerAddressLine',
        'invoicingCustomerAdditionalAddressLine',
        'invoicingCustomerPostNumber',
        'invoicingCustomerTown',
        'invoicingCustomerCountryCode',
        'deliveryAddressName',
        'deliveryAddressLine',
        'deliveryAddressPostNumber',
        'deliveryAddressTown',
        'deliveryAddressCountryCode',
        'deliveryMethod',
        'deliveryTerm',
        'salesInvoiceTaxHandlingType',
        'paymentTermCashDiscountDays',
        'paymentTermCashDiscount',
        'expectPartialPayments',
        'overrideVoucherSalesReceivablesAccountNumber',
        'salesInvoiceAgreementIdentifier',
        'printChannelFormat',
        'secondName',
    ];

    private $salesInvoiceDate;
    private $salesInvoiceAmount;
    private $salesInvoiceStatus;
    private $invoicingCustomerIdentifier;
    private $paymentTermNetDays;

    /**
     * @XmlKeyValuePairs
     * @Inline
     */
    private $additionalFields;

    /**
     * @XmlList(entry = "invoiceline")
     */
    private $invoiceLines = array();

    /**
     * @param \DateTime $salesInvoiceDate
     * @param string $salesInvoiceAmount
     * @param string $salesInvoiceStatus
     * @param string $invoicingCustomerIdentifier
     * @param int $paymentTermNetDays
     * @param array $additionalFields
     */
    public function __construct(
        \DateTime $salesInvoiceDate,
        $salesInvoiceAmount,
        $salesInvoiceStatus,
        $invoicingCustomerIdentifier,
        $paymentTermNetDays,
        array $additionalFields = []
    ) {
        $this->salesInvoiceDate = $salesInvoiceDate->format('Y-m-d');
        $this->salesInvoiceAmount = $salesInvoiceAmount;
        $this->salesInvoiceStatus = new AttributeElement($salesInvoiceStatus, array('type' => 'netvisor'));
        $this->invoicingCustomerIdentifier = new AttributeElement($invoicingCustomerIdentifier, array('type' => 'netvisor')); // TODO: Type can be netvisor/customer.
        $this->paymentTermNetDays = $paymentTermNetDays;

        $this->additionalFields = array_change_key_case(
            array_filter(
                $additionalFields,
                function ($key) {
                    return in_array($key, self::ALLOWED_ADDITIONAL_FIELDS, true);
                },
                ARRAY_FILTER_USE_KEY
            )
        );
    }

    /**
     * @param SalesInvoiceProductLine $line
     */
    public function addSalesInvoiceProductLine(SalesInvoiceProductLine $line)
    {
        $this->invoiceLines[] = new WrapperElement('salesinvoiceproductline', $line);
    }

    public function getDtdPath()
    {
        return $this->getDtdFile('salesinvoice.dtd');
    }

    protected function getXmlName()
    {
        return 'salesinvoice';
    }
}
