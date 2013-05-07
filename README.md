# Xi Netvisor

Netvisor API interface for PHP 5.3+.

## Interfaces

- Voucher

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
netvisor.interface.host           = "https://www.netvisor.biz"         // Test server is "http://integrationdemo.netvisor.fi"
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

### XML

You can instantiate a certain type (e.g. Voucher).
After building the object, you can call `$voucher->isValid()` to validate.

### Vouchers

```php
$voucher = new Voucher();
// Set all fields

$netvisor->addVoucher($voucher); // send the Voucher to Netvisor