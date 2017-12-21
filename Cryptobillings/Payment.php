<?php
namespace Cryptobillings;

use GuzzleHttp\Client as HttpClient;

class Payment
{
    const BASE_API_URI = 'https://gateway.cryptobillings.com/api';
    const ORDER_CREATE_PATH = 'order/create';
    
    const API_VERSION_1 = 'v1';
    
    const RESPONSE_STATUS_OK = 200;
    const RESPONSE_STATUS_ERROR = 500;
    const RESPONSE_STATUS_DENIED = 403;
    
    const PAYMENT_STATUS_NEW = 0;
    const PAYMENT_STATUS_PENDING = 1;
    const PAYMENT_STATUS_SUCCESS = 2;
    const PAYMENT_STATUS_EXPIRE = 3;
    const PAYMENT_STATUS_CANCEL = 4;
    
    protected $apiVersion = 'v1';

    /**
     *
     * @var string 
     */
    protected $apiKey;
    
    /**
     *
     * @var HttpClient 
     */
    protected $httpClient;
    
    /**
     * 
     * @param string $apiKey Api key provided by cryptobillings.com
     */
    public function __construct($apiKey, $apiVersion = self::API_VERSION_1)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new HttpClient();
        $this->apiVersion = $apiVersion;
    }


    /**
     * This method returns redirect url (on success) where user must be redirected to proceed with payment.
     * 
     * @param float $amountUsd This amount will be converted to dopecoins
     * @param string $description Shows this on a payment screen
     * @param string $successUrl Returns to this url when order is fully paid
     * @param string $cancelUrl Returns to this url when user clicks cancel
     * @param string $notifyUrl Called on any status change
     * @param string $param1 Returned with success, cancel and notify_url
     * @param string $param2 Returned with success, cancel and notify_url
     * @param array Array of Item to show on payment page 
     * @return mixed
     */
    public function createOrder($currency, $amount, $toCryptoCurrency, $description, $successUrl, $cancelUrl, $notifyUrl, $param1 = null, $param2 = null, array $items = [], AddressInfo $shipping = null, AddressInfo $billing = null)            
    {
        $formParams = [
            'api_key' => $this->apiKey,
            'amount' => $amount,
            'currency' => $currency,
            'to_cryptocurrency' => $toCryptoCurrency,
            'description' => $description,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'notify_url' => $notifyUrl,
            'p1' => $param1,
            'p2' => $param2,
            'items' => [],
            'shipping' => [],
            'billing' => []
        ];
        
        if ($items) {
            foreach($items as $item) {
                $formParams['items'][] = [
                    'description' => $item->description,
                    'price' => $item->price,
                    'currency' => $item->currency,
                    'quanity' => $item->quantity
                ];
            }
        }
        
        if ($shipping) {
            $formParams['shipping'] = [
                'name' => $shipping->name,
                'line1' => $shipping->line1,
                'line2' => $shipping->line2,
                'country_code' => $shipping->countryCode,
                'postal_code' => $shipping->postalCode,
                'state' => $shipping->state,
                'phone' => $shipping->phone
            ];
        }
        
        if ($billing) {
            $formParams['billing'] = [
                'name' => $billing->name,
                'line1' => $billing->line1,
                'line2' => $billing->line2,
                'country_code' => $billing->countryCode,
                'postal_code' => $billing->postalCode,
                'state' => $billing->state,
                'phone' => $billing->phone
            ];
        }
        
        $responseJson = $this->httpClient->post(self::BASE_API_URI . '/' . $this->apiVersion . '/' . self::ORDER_CREATE_PATH, $formParams);
        
        return \GuzzleHttp\json_decode($responseJson->getBody());                
    }
}