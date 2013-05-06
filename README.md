# Xi Netvisor

Netvisor API interface for PHP 5.3+.

## Interfaces

- Invoice
- Customer

## Setup

You must do the following things to get everything up and running:

- Get your partner ID and key from Netvisor
- Get access to Netvisor web management page (testing environment has its own management page)
- Activate use of external interfaces in your management page
- Create new interface ID & key with your management page (same as above)
- You can create and manage your products in Netvisor

## Configuration

Most of the configuration parameters should be obtained from Netvisor.

```
netvisor.interface.host           = "https://www.netvisor.biz"         // Test server is "http://koulutus.netvisor.fi"
netvisor.interface.sender         = "Testiclient"                      // Pick a name that describes your service
netvisor.interface.customerId     = "XX_yyyy_1yyy"                     // Create manually in your Netvisor management page
netvisor.interface.partnerId      = "Xxx_yyy"                          // Obtain from your Netvisor contact
netvisor.interface.organizationId = "2521043-1"                        // Your company ID

netvisor.interface.userKey        = "D953E3D10457F778B009F88B038CC3C7" // Create manually in your Netvisor management page
netvisor.interface.partnerKey     = "3BCBFB382CE884YD6C8D4F4FC1C2AC95" // Obtain from your Netvisor contact

netvisor.interface.language       = "FI"
netvisor.interface.enabled        = true
```

## Usage

### Setup

```php
$netvisor = new Xi\Netvisor\Netvisor($configuration);
```

### Invoices
``

```php
$invoice = new Xi\Netvisor\Resource\Invoice($data); // throws Exception if data is not valid
$netvisor->addInvoice($invoice);                    // send the invoice to Netvisor
```

Invoice data should just be an array containing invoice fields:

```php
$data = array(
    'SalesInvoiceNumber'                        => 123456,
    'SalesInvoiceDate'                          => '2011-12-12',
    'SalesInvoiceDeliveryDate'                  => '2011-12-12',
    'SalesInvoiceReferenceNumber'               => 7773,
    'SalesInvoiceAmount'                        => 12,
    'SellerIdentifier'                          => 124124,
    'SellerName'                                => 'Petteri Peppele',
    'SalesInvoiceStatus'                        => 'open',
    'SalesInvoiceFreeTextBeforeLines'           => 'before lines',
    'SalesInvoiceFreeTextAfterLines'            => 'after lines',
    'SalesInvoiceOurReference'                  => 'our reference',
    'SalesInvoiceYourReference'                 => 'your reference',
    'SalesInvoicePrivateComment'                => 'private comment',

    'InvoicingCustomerIdentifier'               => '1',
    'InvoicingCustomerIdentifierType'           => 'netvisor',
    'InvoicingCustomerName'                     => 'customer name',
    'InvoicingCustomerNameExtension'            => 'name extension',
    'InvoicingCustomerAddressLine'              => 'metsälänkuja 5',
    'InvoicingCustomerPostNumber'               => '02593',
    'InvoicingCustomerTown'                     => 'Kepukkala',
    'InvoicingCustomerCountryCode'              => 'FI',                // ISO-3166

    'DeliveryAddressName'                       => 'deliveryaddress name',
    'DeliveryAddressNameExtension'              => 'deliveryadress name extension',
    'DeliveryAddressLine'                       => 'metsälänkuja 6',
    'DeliveryAddressPostNumber'                 => '23563',
    'DeliveryAddressTown'                       => 'Kepukkala',
    'DeliveryAddressCountryCode'                => 'FI',                // ISO-3166

    'DeliveryMethod'                            => 'post',
    'DeliveryTerm'                              => 'delivery term',
    'PaymentTermNetDays'                        => 21,
    'PaymentTermCashDiscountDays'               => 5,
    'PaymentTermCashDiscount'                   => 5,5,
    'ExpectPartialPayments'                     => 1,
    'TryDirectDebitLink'                        => 0,
    'TryDirectDebitLinkMode'                    => 'fail_on_error',

    'InvoiceLines' => array(
        array(
            'ProductIdentifier'                         => '5',
            'ProductIdentifierType'                     => 'netvisor',
            'ProductName'                               => 'Banaani',
            'ProductUnitPrice'                          => 100,
            'ProductUnitPriceType'                      => 'net',
            'ProductVatPercentage'                      => 22,
            'ProductVatPercentageVatCode'               => 'KOMY',
            'SalesInvoiceProductLineQuantity'           => 5,
            'SalesInvoiceProductLineDiscountPercentage' => 10,
            'AccountingAccountSuggestion'               => '3000',
        ),
        array(
            'ProductIdentifier'                         => '5',
            'ProductIdentifierType'                     => 'netvisor',
            'ProductName'                               => 'tuote 2',
            'ProductUnitPrice'                          => 10,
            'ProductUnitPriceType'                      => 'net',
            'ProductVatPercentage'                      => 22,
            'ProductVatPercentageVatCode'               => 'KOMY',
            'SalesInvoiceProductLineQuantity'           => 5,
        ),
    ),

    'Dimension' => array( // TODO: Find out what is a "Dimension"
        array(
            'DimensionName' => 'projekti',
            'DimensionItem' => 'palkanlaskenta',
        ),
         array(
            'DimensionName' => 'projekti2',
            'DimensionItem' => 'palkanlaskenta2',
        ),
    ),

    'InvoiceVoucherLines' => array(
        array(
            'LineSum'       => 100,
            'LineSumType'   => 'net',
            'Description'   => 'invoice voucher line description',
            'AccountNumber' => '1101', // From "tilikartta", pick what is right for your business.
            'VatPercent'    => '22',
            'VatCode'       => 'KOMY',
        ),

    ),

    'SalesInvoiceAttachments' => array(
        array(
            'MimeType'              => 'xxx',
            'AttachmentDescription' => 'cool attachmentt',
            'FileName'              => 'tname of file',
            'DocumentData'          => 'base64encodedShit',
        ),
    ),

    'Tags' => array(
        array(
            'TagName'          => 'tagi',
            'TagValue'         => 'taginvalue',
            'TagValueDataType' => 'text',
        ),
    ),
);
```