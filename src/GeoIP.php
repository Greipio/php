<?php

namespace Greip\API;

use Exception;
use Greip\API\Enums\Language;
use Greip\API\Enums\Mode;
use Greip\API\Enums\Param;

/**
 * GeoIP Class
 *
 * This class will help you use the functionalities of Greip IP Geolocation & Country API
 * For more information please check out https://docs.greip.io
 *
 * @package gre\geoip
 * @version 2.0
 * @author Greip <info@greip.io>
 * @copyright 2016-2024 Greip
 * @license MIT
 */

class GeoIP extends Exception
{
    /**
     * @var string $BaseURL Greip's base URL.
     * @var string $isError Can be used AFTER MAKING A REQUEST to determine if the API returned an error.
     */
    private $BaseURL = "https://greipapi.com/";
    public $isError = false;

    /**
     * IP Geolocation Method
     *
     * @param string $ip The IP Address you want to lookup
     * @param array $params You can pass the modules you want to fetch for that $ip. This array accepts `location`, `security`, `timezone`, `currency` and/or `device`.
     * @param string $lang Sets the output language. It can be `EN`, `AR`, `DE`, `FR`, `ES`, `JA`, `ZH` or `RU`.
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/ip-geolocation/ip-lookup
     *
     * @return array The $ip information
     */
    public function lookup(
        $ip,
        $params = [],
        $lang = Language::EN,
        $mode = Mode::LIVE
    ): array {
        $ip = strtoupper($ip);
        $lang = strtoupper($lang);
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($ip)) {
            $tempParams = [];
            foreach ($params as $perParam) {
                if (!empty($perParam)) {
                    if (!in_array($perParam, Param::values())) {
                        $this->isError = true;
                        throw new Exception(
                            "The `$perParam` param is not valid for the `\$params` argument."
                        );
                    } else {
                        array_push($tempParams, $perParam);
                    }
                }
            }
            $tempParams = implode(",", $tempParams);

            if (!in_array($lang, Language::values())) {
                $this->isError = true;
                throw new Exception(
                    "The language you specified ($lang) is unknown."
                );
            }

            if (!in_array($mode, Mode::values())) {
                $this->isError = true;
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "ip" => $ip,
                "lang" => $lang,
                "params" => $tempParams,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL . "/IPLookup?" . http_build_query($localParams)
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer " . $configClass->getToken(),
            ]);
            $APIResponse = curl_exec($ch);
            curl_close($ch);
            $decodedResponse = json_decode($APIResponse, true);

            if (
                is_array($decodedResponse) &&
                in_array("status", array_keys($decodedResponse)) &&
                $decodedResponse["status"] !== "success"
            ) {
                $this->isError = true;
            }

            return $decodedResponse;
        } else {
            $this->isError = true;
            throw new Exception(
                'The `$ip` parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * IP Threats Method
     *
     * @param string $ip The IP Address you want to retrieve it’s threat intelligence information
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/ip-geolocation/ip-lookup
     *
     * @return array The $ip threats intelligence information
     */
    public function threats($ip, $mode = Mode::LIVE): array
    {
        $ip = strtoupper($ip);
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($ip)) {
            if (!in_array($mode, Mode::values())) {
                $this->isError = true;
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "ip" => $ip,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL . "/threats?" . http_build_query($localParams)
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer " . $configClass->getToken(),
            ]);
            $APIResponse = curl_exec($ch);
            curl_close($ch);
            $decodedResponse = json_decode($APIResponse, true);

            if (
                is_array($decodedResponse) &&
                in_array("status", array_keys($decodedResponse)) &&
                $decodedResponse["status"] !== "success"
            ) {
                $this->isError = true;
            }

            return $decodedResponse;
        } else {
            $this->isError = true;
            throw new Exception(
                'The `$ip` parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * Country Lookup Method
     *
     * @param string $countryCode The ٫ISO 3166-1 alpha-2٫ code format of the country. [Learn More](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     * @param array $params The `params` argument is used to determine the data you need in the response.
     * @param string $lang Sets the output language. It can be `EN`, `AR`, `DE`, `FR`, `ES`, `JA`, `ZH` or `RU`.
     * @param string $mode The mode command is used to in the development stage to simulate the integration process before releasing it to the production environment.
     * @see https://docs.greip.io/api-reference/endpoint/other-services/country-data
     *
     * @return array The country information
     */
    public function country(
        $countryCode,
        $params = [],
        $lang = Language::EN,
        $mode = Mode::LIVE
    ): array {
        $countryCode = strtoupper($countryCode);
        $mode = strtolower($mode);
        $lang = strtoupper($lang);

        $configClass = new Config();

        if (!empty($countryCode)) {
            $tempParams = [];
            foreach ($params as $perParam) {
                if (!empty($perParam)) {
                    if (!in_array($perParam, Param::values())) {
                        throw new Exception(
                            "The `$perParam` param is not valid for the `\$params` argument."
                        );
                    } else {
                        array_push($tempParams, $perParam);
                    }
                }
            }
            $tempParams = implode(",", $tempParams);

            if (!in_array($lang, Language::values())) {
                throw new Exception(
                    "The language you specified ($lang) is unknown."
                );
            }

            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "CountryCode" => $countryCode,
                "params" => $tempParams,
                "lang" => $lang,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL . "/Country?" . http_build_query($localParams)
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer " . $configClass->getToken(),
            ]);
            $APIResponse = curl_exec($ch);
            curl_close($ch);
            $decodedResponse = json_decode($APIResponse, true);

            if (
                is_array($decodedResponse) &&
                in_array("status", array_keys($decodedResponse)) &&
                $decodedResponse["status"] !== "success"
            ) {
                $this->isError = true;
            }

            return $decodedResponse;
        } else {
            throw new Exception(
                'The `$countryCode` parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * ASN Lookup Method
     *
     * @param string $asn The AS Number you want to lookup.
     * @param boolean $isList Set this to `true` if you want to the API to return all IPv4 & IPv6 routes.
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/ip-geolocation/asn-lookup
     *
     * @return array The ASN Data
     */
    public function asn($asn, $isList = false, $mode = Mode::LIVE): array
    {
        $asn = strtoupper($asn);
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($asn)) {
            if (!is_bool($isList)) {
                $isList = false;
            }

            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "asn" => $asn,
                "isList" => $isList ? "yes" : "no",
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL . "/ASNLookup?" . http_build_query($localParams)
            );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer " . $configClass->getToken(),
            ]);
            $APIResponse = curl_exec($ch);
            curl_close($ch);
            $decodedResponse = json_decode($APIResponse, true);

            if (
                is_array($decodedResponse) &&
                in_array("status", array_keys($decodedResponse)) &&
                $decodedResponse["status"] !== "success"
            ) {
                $this->isError = true;
            }

            return $decodedResponse;
        } else {
            $this->isError = true;
            throw new Exception(
                'The `$asn` parameter is required. You passed an empty value.'
            );
        }
    }
}
