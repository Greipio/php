<div align="center">
    <h1>GRE GeoIP for PHP</h1>
    <p>The official PHP library for GRE GeoIP API</p>
    <br />
    <a href="https://github.com/gre-dev/GeoIP-PHP/issues/new">Report Issue</a> · 
    <a href="https://github.com/gre-dev/GeoIP-PHP/discussions/new">Request Feature</a> · 
    <a href="https://www.gredev.io/en/GeoIP" target="_BLANK">API Home Page</a> · 
    <a href="https://geoip-docs.gredev.io/sdks/php" target="_BLANK">API Docs</a>
    <br />
    <br />
    <a href="https://packagist.org/packages/gre/geoip" title="NPM Package" href="_BLANK"><img src="https://img.shields.io/badge/packagist-CB3837?style=for-the-badge&logo=packagist&logoColor=white&color=f28d1a"></a>
    <img src="https://img.shields.io/badge/php-CB3837?style=for-the-badge&logo=php&logoColor=white&color=4F5B93" title="Javascript">
    <a href="https://github.com/gre-dev/GeoIP-PHP" title="Github Repo" href="_BLANK"><img src="https://img.shields.io/badge/GitHub-CB3837?style=for-the-badge&logo=github&logoColor=white&color=black"></a>
</div>
<br />

---
<br />

![Packagist Version](https://img.shields.io/packagist/v/gre/geoip?color=brightgreen&label=Stable&logo=packagist&logoColor=white)
&nbsp;&nbsp;
![GitHub code size in bytes](https://img.shields.io/github/languages/code-size/gre-dev/GeoIP-PHP?color=brightgreen&label=Size&logo=packagist&logoColor=white)
&nbsp;&nbsp;
![API Status](https://img.shields.io/website?down_color=orange&down_message=down&label=API%20status&up_color=brightgreen&up_message=up&url=https%3A%2F%2Fgregeoip.com)
&nbsp;&nbsp;
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
&nbsp;&nbsp;
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/gre/geoip?color=blue)
<br /><br />

# Requirements
* php: >=7.4
<br /><br />

# Installation
```
composer require gre/geoip
```
<br /><br />

# Usage
Let's say that we want to get the information of a specific IP Address. So we do the following:
<br /><br />

```php
include_once './vendor/autoload.php';

// Using the library
use GRE\GeoIP\GeoIP;

// Declaring the library's class
$GREGeoIP = new GeoIP();
// Setting the API Key
$GREGeoIP->setKey('<API-Key>');

// Sending the request and storing the output in a variable
$GeoIP_Response = $GREGeoIP->lookup('1.1.1.1');
// Printing the reponse
print_r($GeoIP_Response);
```
<br /><br />

# Options, Methods and More
You can find the full guide of this package by visiting our [Documentation Page](https://geoip-docs.gredev.io/sdks/php).

<br /><br />
# Credits
* [GRE Development Ltd.](https://www.gredev.io/en/)
* [All Contributors](https://github.com/gre-dev/GeoIP-PHP/graphs/contributors)
<br /><br />

# License
The MIT License (MIT). Please see [License](https://github.com/gre-dev/GeoIP-PHP/blob/main/LICENSE) File for more information.