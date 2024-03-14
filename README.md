# Greip PHP Library

The official PHP library for Greip API

[Report Issue](https://github.com/Greipio/php/issues/new)
·
[Request Feature](https://github.com/Greipio/php/discussions/new?category=ideas)
·
[Greip Website](https://greip.io/)
·
[Documentation](https://docs.greip.io/)

[![Packagist](https://img.shields.io/badge/packagist-CB3837?style=for-the-badge&logo=packagist&logoColor=white&color=f28d1a)](https://packagist.org/packages/gre/geoip)
[![Github Repository](https://img.shields.io/badge/GitHub-CB3837?style=for-the-badge&logo=github&logoColor=white&color=black)](https://github.com/Greipio/php)

![Packagist Version](https://img.shields.io/packagist/v/gre/geoip?color=brightgreen&label=Stable&logo=packagist&logoColor=white)
&nbsp;&nbsp;
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/Greipio/php?color=brightgreen&label=Size&logo=packagist&logoColor=white)
&nbsp;&nbsp;
![API Status](https://img.shields.io/website?down_color=orange&down_message=down&label=API%20status&up_color=brightgreen&up_message=up&url=https%3A%2F%greipapi.com)
&nbsp;&nbsp;
[![License: Apache 2.0](https://img.shields.io/badge/License-Apache_2.0-blue.svg)](https://opensource.org/license/apache-2-0)
&nbsp;&nbsp;
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/gre/geoip?color=blue)

---

## Installation

```
composer require gre/geoip
```

## Usage

Here's how you use the library:
<br />

### 1. IP Geolocation

Use this method to retrieve the information of a given IP address.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$GeoIP = new Greip\API\GeoIP();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$GeoIP_Response = $GeoIP->lookup("1.1.1.1");

// Printing the response
print_r($GeoIP_Response);
```

### 2. IP Threats

Use this method to retrieve threat intelligence information associated with a given IP address.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$GeoIP = new Greip\API\GeoIP();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$GeoIP_Response = $GeoIP->threats("1.1.1.1");

// Printing the response
print_r($GeoIP_Response);
```

### 3. ASN Lookup

In this method, Greip will help you lookup any given AS Number and returning all data related to it, like: name, org (the organization name), country, domain, email, phone, totalIPs, list of all routes (v4 & v6) related the given AS Number, etc.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$GeoIP = new Greip\API\GeoIP();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$ASN_Response = $GeoIP->asn("AS01");

// Printing the response
print_r($ASN_Response);
```

### 4. Country Lookup

This method can help you retrieve information of the given country.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$GeoIP = new Greip\API\GeoIP();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$Country_Response = $GeoIP->country("US", ["language", "timezone", "currency"]);

// Printing the response
print_r($Country_Response);
```

### 5. Email Validation

This method provides an additional layer of validation for your system. While validating email syntax is important, it is not sufficient.

This method goes beyond syntax validation by checking the domain’s validity, the availability of the Mail Service, detecting Disposable Email (Temporary Emails), etc. By utilising this method, you can ensure a more thorough validation process for email addresses.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$Fraud = new Greip\API\Fraud();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$Email_Response = $Fraud->email("example@domain.com");

// Printing the response
print_r($Email_Response);
```

### 6. Phone Validation

This method can be used as an extra-layer of your system for validating phone numbers. It validates phone number syntax and valid-possibility.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$Fraud = new Greip\API\Fraud();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$Phone_Response = $Fraud->phone("000000000", "US");

// Printing the response
print_r($Phone_Response);
```

### 7. Profanity Detection

This method can be used to detect abuse of your website/app. It’s a great way to know more about your user inputs and whether they contain profanity (bad words) or not before releasing them to the public.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$Fraud = new Greip\API\Fraud();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$Profanity_Response = $Fraud->profanity("This is a sample text", true, false);

// Printing the response
print_r($Profanity_Response);
```

### 8. Payment Fraud Prevention

Prevent financial losses by deploying AI-Powered modules.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$Fraud = new Greip\API\Fraud();

// Setting the API Key
$config->setToken("<API-Key>");

// Declaring Transaction Data
$data = [
  "action" => "purchase",
  "website_domain" => "example.com",
  "merchant_id" => 21,
  "customer_id" => 1,
  "customer_ip" => "1.0.0.2",
  "customer_email" => "asdfasdf@gmasail.com",
  "customer_phone" => "32423434",
  "customer_country" => "US",
  "transaction_amount" => 100000,
  "transaction_currency" => "USD",
  "customer_useragent" => "Mozill almaden sdfwer",
];

// Sending the request and storing the output in a variable
$Payment_Response = $Fraud->payment($data);

// Printing the response
print_r($Payment_Response);
```

### 9. IBAN Validation

This method allows you to validate International Bank Account Numbers (IBANs) and retrieve additional information about the country associated with the IBAN.

```php
include_once __DIR__ . "/vendor/autoload.php";

// Declaring the classes we need
$config = new Greip\API\Config();
$Fraud = new Greip\API\Fraud();

// Setting the API Key
$config->setToken("<API-Key>");

// Sending the request and storing the output in a variable
$IBAN_Response = $Fraud->iban("FO9264600123456789");

// Printing the response
print_r($IBAN_Response);
```

## Options, Methods and More

You can find the full guide of this package by visiting our [Documentation Page](https://docs.greip.io/).

## Credits

- [Greip Team](https://greip.io/)
- [All Contributors](https://github.com/Greipio/php/graphs/contributors)
