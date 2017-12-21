<?php
namespace lexerom\cryptobillings;

class Item
{
    public $description;
    public $price;
    public $currency;
    public $quantity;
    
    public function __construct($description, $price, $currency, $quantity = 1)
    {
        $this->description = $description;
        $this->price = $price;
        $this->currency = $currency;
        $this->quantity = $quantity;
    }
}
