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
    // Note that the order is meaningful
    const FIELDS = [
        'salesInvoiceNumber',
        'salesInvoiceDate',
        'salesInvoiceDeliveryDate',
        'salesInvoiceReferenceNumber',
        'salesInvoiceAmount',
        'sellerName',
        'invoiceType',
        'salesInvoiceStatus',
        'salesInvoiceFreeTextBeforeLines',
        'salesInvoiceFreeTextAfterLines',
        'salesInvoiceOurReference',
        'salesInvoiceYourReference',
        'salesInvoicePrivateComment',
        'invoicingCustomerIdentifier',
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
        'paymentTermNetDays',
        'paymentTermCashDiscountDays',
        'paymentTermCashDiscount',
        'expectPartialPayments',
        'overrideVoucherSalesReceivablesAccountNumber',
        'salesInvoiceAgreementIdentifier',
        'printChannelFormat',
        'secondName',
    ];

    /**
     * @XmlKeyValuePairs
     * @Inline
     */
    private $data;

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
        $requiredAndTransformed = [
            'salesInvoiceDate' => $salesInvoiceDate->format('Y-m-d'),
            'salesInvoiceAmount' => $salesInvoiceAmount,
            'salesInvoiceStatus' => new AttributeElement($salesInvoiceStatus, array('type' => 'netvisor')),
            'invoicingCustomerIdentifier' => new AttributeElement($invoicingCustomerIdentifier, array('type' => 'netvisor')), // TODO: Type can be netvisor/customer.
            'paymentTermNetDays' => $paymentTermNetDays,
            'secondName' => array_key_exists('secondName', $additionalFields) ? new AttributeElement($additionalFields['secondName'], ['type' => 'netvisor']) : null,
        ];

        $data = array_merge(
            array_filter(
                $additionalFields,
                function ($key) {
                    return in_array($key, self::FIELDS, true);
                },
                ARRAY_FILTER_USE_KEY
            ),
            $requiredAndTransformed
        );

        uksort($data, function ($a, $b) {
            return array_search($a, self::FIELDS) - array_search($b, self::FIELDS);
        });

        $this->data = array_change_key_case($data);
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
