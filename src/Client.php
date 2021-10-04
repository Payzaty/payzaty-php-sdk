<?php

namespace Payzaty;

use Payzaty\Response\CheckoutResponse;
use Payzaty\Response\Message;
use Payzaty\Response\StatusResponse;

class Client
{

    private $baseUrl;
    private $merchantNo;
    private $secretKey;
    private $language;

    public function __construct($merchantNo, $secretKey, $language, $sandbox)
    {
        $this->baseUrl = $sandbox ? 'https://sandbox.payzaty.com' : 'https://www.payzaty.com';
        $this->merchantNo = $merchantNo;
        $this->secretKey = $secretKey;
        $this->language = $language;
    }


    public function checkout($name, $email, $phoneCode, $phoneNumber, $amount, $currencyID, $responseUrl, $UDF1 = "", $UDF2 = "", $UDF3 = "")
    {
        $fields = [
            "Name" => $name, "Email" => $email, "PhoneCode" => $phoneCode, "PhoneNumber" => $phoneNumber,
            "Amount" => $amount, "CurrencyID" => $currencyID, "UDF1" => $UDF1, "UDF2" => $UDF2, "UDF3" => $UDF3,
            "ResponseUrl" => $responseUrl,
        ];

        $url = $this->baseUrl . '/payment/checkout';

        return $this->postCall($url, $fields);
    }

    public function getStatus($checkoutId)
    {
        if (empty($checkoutId)) {
            throw new \Exception('CheckoutId cannot be empty');
        }

        $url = $this->baseUrl . '/payment/status/' . $checkoutId;

        return $this->getCall($url);
    }

    private function getCall($url)
    {

        $header = [
            'X-Source:5', 'X-Build:1', 'X-Version:1',
            'X-Language: ' . $this->language,
            'X-MerchantNo:' . $this->merchantNo, 'X-SecretKey:' . $this->secretKey
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);

        return new StatusResponse($response);

    }

    private function postCall($url, $data = [])
    {

        $header = array(
            'X-Source: 5',
            'X-Build: 1',
            'X-Version: 1',
            'X-Language: ' . $this->language,
            'X-MerchantNo: ' . $this->merchantNo,
            'X-SecretKey: ' . $this->secretKey,
            'Content-Type: application/x-www-form-urlencoded'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);

        return new CheckoutResponse($response);

    }

}
