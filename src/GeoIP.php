<?php
namespace GRE;
use Exception;

/**
 * GeoIP Class
 *
 * This class will help you use the functionalities of the GeoIP API
 * For more information please check out https://geoip-docs.gredev.io
 * 
 * @package gre\geoip
 * @version 1.1.1
 * @author GRE Development Ltd. <info@gredev.io>
 * @copyright 2016-2022 GRE Development Ltd.
 * @license MIT
 */

class GeoIP extends Exception
{

   /**
    * @var string $APIKey This is your API Key.
    * @var string $APIEndpoint The GRE GeoIP endpoint.
    * @var array $AvailableGeoIPParams List of the params available to use in both GeoIP & Lookup modules of the API.
    * @var array $AvailableCountryParams List of the params available to use in the Country module of the API.
    * @var string $isError Can be used AFTER MAKING A REQUEST to determine if the API returned an error.
    */
   private $APIKey = '';
   private $APIEndpoint = 'https://gregeoip.com/';
   private $AvailableGeoIPParams = ['security', 'timezone', 'currency', 'device'];
   private $AvailableCountryParams = ['language', 'flag', 'currency', 'timezone'];
   public $isError = false;

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
         $this->APIKey = $key;
         return true;
      } else {
         return false;
      }
   }

   /**
    * lookup method 
    *
    * @param string $ip The IP Address you want to lookup
    * @param array $params You can pass the modules you want to fetch for that $ip. This array accepts `security`, `timezone`, `currency` and/or `device`.
    * @param string $lang Sets the output language. It can be `EN`, `AR`, `DE`, `FR`, `ES`, `JA`, `ZH` or `RU`.
    *
    * @return array The $ip information
    */
   public function lookup($ip, $params = [], $lang = 'EN'): array
   {
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
         $localParams = array(
            'key' => $this->APIKey,
            'ip' => $ip,
            'lang' => $lang,
            'params' => $tempParams
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
    * country method 
    *
    * @param string $countryCode The ISO 3166-1 alpha-2 code format of the country. [Learn More](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
    * @param array $params You can pass the modules you want to fetch for that $ip. This array accepts `security`, `timezone`, `currency` and/or `device`.
    * @param string $lang Sets the output language. It can be `EN`, `AR`, `DE`, `FR`, `ES`, `JA`, `ZH` or `RU`.
    *
    * @return array The country information
    */
   public function country($countryCode, $params = [], $lang = 'EN'): array
   {
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
         $localParams = array(
            'key' => $this->APIKey,
            'CountryCode' => $countryCode,
            'lang' => $lang,
            'params' => $tempParams
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
}
