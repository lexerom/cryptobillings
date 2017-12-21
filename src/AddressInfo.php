<?php
namespace Cryptobillings;

class AddressInfo
{
    const TYPE_BILLING = 'billing';
    const TYPE_SHIPPING = 'shipping';
    
    public $name;
    public $line1, $line2;
    public $city;
    public $countryCode;
    public $postalCode;
    public $state;
    public $phone;
    public $type;        
}

