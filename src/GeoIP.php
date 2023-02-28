<?php

namespace Greip;

use Exception;

class Config
{
   public static $APIKey = '';

   /**
    * setKey method 
    *
    * @param string $key Pass you API Key as a string here. You can also store it in a .env file and pass a variable that returns the API Key as a string.
    *
    * @return bool
    */
   public function setKey($key): bool
   {
      if (!empty($key)) {
         self::$APIKey = $key;
         return true;
      } else {
         return false;
      }
   }

   public function getKey(): string
   {
      return self::$APIKey;
   }
}

/**
 * GeoIP Class
 *
 * This class will help you use the functionalities of Greip IP Geolocation & Country API
 * For more information please check out https://docs.greip.io
 * 
 * @package gre\geoip
 * @version 2.0
 * @author Greip <info@greip.io>
 * @copyright 2016-2023 Greip
 * @license MIT
 */

class GeoIP extends Exception
{
   /**
    * @var string $APIEndpoint The GRE GeoIP endpoint.
    * @var array $AvailableGeoIPParams List of the params available to use in both GeoIP & Lookup modules of the API.
    * @var array $AvailableCountryParams List of the params available to use in the Country module of the API.
    * @var string $isError Can be used AFTER MAKING A REQUEST to determine if the API returned an error.
    */
   private $APIEndpoint = 'https://gregeoip.com/';
   private $AvailableGeoIPParams = ['location', 'security', 'timezone', 'currency', 'device'];
   private $AvailableCountryParams = ['language', 'flag', 'currency', 'timezone'];
   private $AvailableLanguages = ['EN', 'AR', 'DE', 'FR', 'ES', 'JA', 'ZH', 'RU'];
   public $isError = false;

   /**
    * IP Geolocation Method 
    *
    * @param string $ip The IP Address you want to lookup
    * @param array $params You can pass the modules you want to fetch for that $ip. This array accepts `location`, `security`, `timezone`, `currency` and/or `device`.
    * @param string $lang Sets the output language. It can be `EN`, `AR`, `DE`, `FR`, `ES`, `JA`, `ZH` or `RU`.
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/lookup-ip-address
    *
    * @return array The $ip information
    */
   public function lookup($ip, $params = [], $lang = 'EN', $mode = 'live'): array
   {
      $configClass = new Config();

      if (!empty($ip)) {
         $tempParams = '';
         foreach ($params as $perParam) {
            if (!empty($perParam)) {
               if (!in_array($perParam, $this->AvailableGeoIPParams)) {
                  $this->isError = true;
                  throw new Exception('The $params variable is not valid. You passed `' . $perParam . '` which is unknown.');
               } else {
                  $tempParams .= $perParam . ',';
               }
            }
         }
         if (!empty($tempParams)) $tempParams = substr($tempParams, 0, -1);

         if (!in_array($lang, $this->AvailableLanguages)) {
            $this->isError = true;
            throw new Exception('The language you specified (' . $lang . ') is unknown.');
         }

         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         $localParams = array(
            'key' => $configClass->getKey(),
            'ip' => $ip,
            'lang' => $lang,
            'params' => $tempParams,
            'mode' => $mode,
            'source' => 'PHP-SDK'
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'IPLookup?' . http_build_query($localParams));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $ip parameter is required. You passed an empty value.');
      }
   }

   /**
    * Country Lookup Method 
    *
    * @param string $countryCode The ISO 3166-1 alpha-2 code format of the country. [Learn More](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
    * @param array $params You can pass the modules you want to fetch for that $ip. This array accepts `location`, `security`, `timezone`, `currency` and/or `device`.
    * @param string $lang Sets the output language. It can be `EN`, `AR`, `DE`, `FR`, `ES`, `JA`, `ZH` or `RU`.
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/country-data-api
    *
    * @return array The country information
    */
   public function country($countryCode, $params = [], $lang = 'EN', $mode = 'live'): array
   {
      $configClass = new Config();

      if (!empty($countryCode)) {
         $tempParams = '';
         foreach ($params as $perParam) {
            if (!empty($perParam)) {
               if (!in_array($perParam, $this->AvailableCountryParams)) {
                  $this->isError = true;
                  throw new Exception('The $params variable is not valid. You passed `' . $perParam . '` which is unknown.');
               } else {
                  $tempParams .= $perParam . ',';
               }
            }
         }
         if (!empty($tempParams)) $tempParams = substr($tempParams, 0, -1);

         if (!in_array($lang, $this->AvailableLanguages)) {
            $this->isError = true;
            throw new Exception('The language you specified (' . $lang . ') is unknown.');
         }

         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         $localParams = array(
            'key' => $configClass->getKey(),
            'CountryCode' => $countryCode,
            'params' => $tempParams,
            'lang' => $lang,
            'mode' => $mode,
            'source' => 'PHP-SDK'
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'Country?' . http_build_query($localParams));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $countryCode parameter is required. You passed an empty value.');
      }
   }

   /**
    * ASN Lookup Method 
    *
    * @param string $asn The AS Number you want to lookup.
    * @param boolean $isList Set this to `true` if you want to the API to return all IPv4 & IPv6 routes.
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/asn-lookup-api
    *
    * @return array The ASN Data
    */
   public function asn($asn, $isList = false, $mode = 'live'): array
   {
      $configClass = new Config();

      if (!empty($asn)) {
         if (!is_bool($isList)) $isList = false;

         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         $localParams = array(
            'key' => $configClass->getKey(),
            'asn' => $asn,
            'isList' => ($isList) ? 'yes' : 'no',
            'mode' => $mode,
            'source' => 'PHP-SDK'
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'ASNLookup?' . http_build_query($localParams));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $asn parameter is required. You passed an empty value.');
      }
   }
}

/**
 * Fraud Class
 *
 * This class will help you use the functionalities of Greip Fraud Prevention
 * For more information please check out https://docs.greip.io
 * 
 * @package gre\geoip
 * @version 2.0
 * @author Greip <info@greip.io>
 * @copyright 2016-2023 Greip
 * @license MIT
 */

class Fraud extends Exception
{
   /**
    * @var string $APIEndpoint The GRE GeoIP endpoint.
    * @var array $AvailableGeoIPParams List of the params available to use in both GeoIP & Lookup modules of the API.
    * @var array $AvailableCountryParams List of the params available to use in the Country module of the API.
    * @var string $isError Can be used AFTER MAKING A REQUEST to determine if the API returned an error.
    */
   private $APIEndpoint = 'https://gregeoip.com/';
   private $AvailableCountryParams = ['language', 'flag', 'currency', 'timezone'];
   private $AvailableLanguages = ['EN', 'AR', 'DE', 'FR', 'ES', 'JA', 'ZH', 'RU'];
   public $isError = false;

   /**
    * Email Validation Method 
    *
    * @param string $email The email addresss you want to validate.
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/data-validation/email-validation
    *
    * @return array
    */
   public function email($email, $mode = 'live'): array|null
   {
      $configClass = new Config();

      if (!empty($email)) {
         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         $localParams = array(
            'key' => $configClass->getKey(),
            'email' => $email,
            'mode' => $mode,
            'source' => 'PHP-SDK'
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'validateEmail?' . http_build_query($localParams));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $phone parameter is required. You passed an empty value.');
      }
   }

   /**
    * Phone Validation Method 
    *
    * @param string $phone The phone number you want to validate.
    * @param string $countryCode The ISO 3166-1 alpha-2 code format of the user. [Learn More](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/data-validation/phone-number-validation
    *
    * @return array
    */
   public function phone($phone, $countryCode, $mode = 'live'): array|null
   {
      $configClass = new Config();

      if (!empty($phone) && !empty($countryCode)) {
         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         $localParams = array(
            'key' => $configClass->getKey(),
            'phone' => $phone,
            'countryCode' => $countryCode,
            'mode' => $mode,
            'source' => 'PHP-SDK'
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'validatePhone?' . http_build_query($localParams));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $phone/$countryCode parameter is required. You passed an empty value.');
      }
   }

   /**
    * Profanity Detection Method 
    *
    * @param string $text The text you want to check for profanity.
    * @param string $listBadWords Used to list the detected words and prases as an Array.
    * @param string $scoreOnly Returns only the score of the text and whether it's safe or not.
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/profanity-detection-api
    *
    * @return array
    */
   public function profanity($text, $listBadWords = false, $scoreOnly = false, $mode = 'live'): array|null
   {
      $configClass = new Config();

      if (!empty($text)) {
         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         if (!is_bool($listBadWords)) $listBadWords = false;
         if (!is_bool($scoreOnly)) $scoreOnly = false;

         $localParams = array(
            'key' => $configClass->getKey(),
            'text' => $text,
            'listBadWords' => ($listBadWords) ? 'yes' : 'no',
            'scoreOnly' => ($scoreOnly) ? 'yes' : 'no',
            'mode' => $mode,
            'source' => 'PHP-SDK'
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'badWords?' . http_build_query($localParams));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $text parameter is required. You passed an empty value.');
      }
   }

   /**
    * Payment Fraud Prevention Method 
    *
    * @param array $data The infomation of the transaction.
    * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
    * @see https://docs.greip.io/methods/payment-fraud-prevention
    *
    * @return array
    */
   public function payment($data, $mode = 'live'): array|null
   {
      $configClass = new Config();

      if (!empty($data)) {
         if ($mode !== 'live' && $mode !== 'test') {
            $this->isError = true;
            throw new Exception('The mode you specified (' . $mode . ') is unknown. You should use `live` or `test`.');
         }

         $localParams = array(
            'key' => $configClass->getKey(),
            'mode' => $mode,
            'source' => 'PHP-SDK',
            'data' => $data,
         );
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $this->APIEndpoint . 'paymentFraud');
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($localParams, true));
         $APIResponse = curl_exec($ch);
         curl_close($ch);
         $decodedResponse = json_decode($APIResponse, true);

         if (is_array($decodedResponse) && in_array('status', array_keys($decodedResponse)) && $decodedResponse['status'] !== 'success') {
            $this->isError = true;
         }

         return $decodedResponse;
      } else {
         $this->isError = true;
         throw new Exception('The $data parameter is required. You passed an empty value.');
      }
   }
}
