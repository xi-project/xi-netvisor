# Xi Netvisor

Netvisor API interface for PHP 5.6+.

[![Build Status](https://secure.travis-ci.org/xi-project/xi-netvisor.png)](https://travis-ci.org/xi-project/xi-netvisor)

## Before you start hacking away

You must do the following things to get everything up and running:

- Get your partner ID and key from Netvisor
- Get access to Netvisor web management page (testing environment has its own management page)
- Activate use of external interfaces in your management page
- Create new interface ID & key with your management page (same as above).

## Usage

### Configuration

Most of the configuration parameters should be obtained from Netvisor.

```
netvisor.host           = "https://www.netvisor.biz"         // Test server is "http://integrationdemo.netvisor.fi"
netvisor.sender         = "Testiclient"                      // Pick a name that describes your service
netvisor.customerId     = "XX_yyyy_1yyy"                     // Create manually in your Netvisor management page
netvisor.partnerId      = "Xxx_yyy"                          // Obtain from your Netvisor contact
netvisor.organizationId = "2521043-1"                        // Your company ID

netvisor.userKey        = "D953E3D10457F778B009F88B038CC3C7" // Create manually in your Netvisor management page
netvisor.partnerKey     = "3BCBFB382CE884YD6C8D4F4FC1C2AC95" // Obtain from your Netvisor contact

netvisor.language       = "FI"
netvisor.enabled        = true
```

### Initialization

```php
$config   = new Xi\Netvisor\Config(...);       // Use the parameters described above.
$netvisor = new Xi\Netvisor\Netvisor($config);
```

### Actions

#### Search customers
```php
$customers = $netvisor->getCustomers(
    '1234567-1' // Optional keyword
);
```

#### Get customers changed since datetime
```php
$customers = $netvisor->getCustomersChangedSince(
    new DateTime(...) // Required
);
```

#### Get product
```php
$product = $netvisor->getProduct(
    123 // Required Netvisor identifier
);
```

#### Send customer
```php
$customer = new Xi\Netvisor\Resource\Xml\Customer(
    new Xi\Netvisor\Resource\Xml\CustomerBaseInformation(
        '1234567-1',
        'Test Oy',
        'Test street 1',
        'Helsinki',
        '00240',
        'FI'
    )
);

$response = new \SimpleXMLElement($this->netvisor->sendCustomer($customer));
$netvisorIdentifier = (string)$response->Replies->InsertedDataIdentifier;
```


#### Send invoice

```php
$invoice = new Xi\Netvisor\Resource\Xml\SalesInvoice(...);

$invoiceProductLine = new Xi\Netvisor\Resource\Xml\SalesInvoiceProductLine(...);

$invoice->addSalesInvoiceProductLine($invoiceProductLine);

$netvisor->sendInvoice($invoice);
```
