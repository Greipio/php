<?php

namespace Greip\API;

use Exception;
use Greip\API\Enums\Mode;

/**
 * Fraud Class
 *
 * This class will help you use the functionalities of Greip Fraud Prevention
 * For more information please check out https://docs.greip.io
 *
 * @package gre\geoip
 * @version 2.0
 * @author Greip <info@greip.io>
 * @copyright 2016-2024 Greip
 * @license MIT
 */

class Fraud extends Exception
{
    /**
     * @var string $BaseURL Greip's base URL.
     * @var string $isError Can be used AFTER MAKING A REQUEST to determine if the API returned an error.
     */
    private $BaseURL = "https://gregeoip.com/";
    public $isError = false;

    /**
     * Email Validation Method
     *
     * @param string $email The email addresss you want to validate.
     * @param string $userID The User Identifier [Learn More](https://docs.greip.io/options/user-identifier).
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/data-validation/email-lookup
     *
     * @return array
     */
    public function email(
        $email,
        $userID = null,
        $mode = Mode::LIVE
    ): array|null {
        $email = strtolower($email);
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($email)) {
            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "email" => $email,
                "userID" => $userID,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL .
                    "/validateEmail?" .
                    http_build_query($localParams)
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
                'The $phone parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * Phone Validation Method
     *
     * @param string $phone The phone number you want to validate.
     * @param string $countryCode The ISO 3166-1 alpha-2 code format of the user. [Learn More](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2)
     * @param string $userID The User Identifier [Learn More](https://docs.greip.io/options/user-identifier).
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/data-validation/phone-lookup
     *
     * @return array
     */
    public function phone(
        $phone,
        $countryCode,
        $userID = null,
        $mode = Mode::LIVE
    ): array|null {
        $phone = strtolower($phone);
        $countryCode = strtoupper($countryCode);
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($phone) && !empty($countryCode)) {
            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "phone" => $phone,
                "countryCode" => $countryCode,
                "userID" => $userID,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL .
                    "/validatePhone?" .
                    http_build_query($localParams)
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
                'The $phone/$countryCode parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * IBAN Validation Method
     *
     * @param string $iban The IBAN you want to validate.
     * @param string $userID The User Identifier [Learn More](https://docs.greip.io/options/user-identifier).
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/payment-fraud/iban-validation
     *
     * @return array
     */
    public function iban($iban, $userID = null, $mode = Mode::LIVE): array|null
    {
        $iban = strtoupper($iban);
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($iban)) {
            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "iban" => $iban,
                "userID" => $userID,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL .
                    "/validateIBAN?" .
                    http_build_query($localParams)
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
                'The `$iban` parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * Profanity Detection Method
     *
     * @param string $text The text you want to check for profanity.
     * @param string $listBadWords Used to list the detected words and prases as an Array.
     * @param string $scoreOnly Returns only the score of the text and whether it's safe or not.
     * @param string $userID The User Identifier [Learn More](https://docs.greip.io/options/user-identifier).
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/other-services/profanity-detection
     *
     * @return array
     */
    public function profanity(
        $text,
        $listBadWords = false,
        $scoreOnly = false,
        $userID = null,
        $mode = Mode::LIVE
    ): array|null {
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($text)) {
            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            if (!is_bool($listBadWords)) {
                $listBadWords = false;
            }
            if (!is_bool($scoreOnly)) {
                $scoreOnly = false;
            }

            $localParams = [
                "text" => $text,
                "listBadWords" => $listBadWords ? "yes" : "no",
                "scoreOnly" => $scoreOnly ? "yes" : "no",
                "userID" => $userID,
                "mode" => $mode,
                "source" => "PHP-SDK",
            ];
            $ch = curl_init();
            curl_setopt(
                $ch,
                CURLOPT_URL,
                $this->BaseURL . "/badWords?" . http_build_query($localParams)
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
                'The $text parameter is required. You passed an empty value.'
            );
        }
    }

    /**
     * Payment Fraud Prevention Method
     *
     * @param array $data The infomation of the transaction.
     * @param string $mode You pass `test` to this variable, so your account plan will not be affected while integrating the library and the API will return fake information for this case. You can set it to `live` again to back to the `production` mode.
     * @see https://docs.greip.io/api-reference/endpoint/payment-fraud/payment-fraud-prevention
     *
     * @return array
     */
    public function payment($data, $mode = Mode::LIVE): array|null
    {
        $mode = strtolower($mode);

        $configClass = new Config();

        if (!empty($data)) {
            if (!in_array($mode, Mode::values())) {
                throw new Exception(
                    "The mode you specified ($mode) is unknown. You should use `live` or `test`."
                );
            }

            $localParams = [
                "mode" => $mode,
                "source" => "PHP-SDK",
                "data" => $data,
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->BaseURL . "/paymentFraud");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                json_encode($localParams, true)
            );
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
                'The $data parameter is required. You passed an empty value.'
            );
        }
    }
}
