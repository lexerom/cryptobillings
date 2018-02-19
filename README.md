#cryptobillings
Cryptobillings is used to receive payments with cryptocurrencies.

Create an account on [Cryptobillings site](https://cryptobillings.com/)

You can install it via composer
```
composer require lexerom/cryptobillings
```

Here is simple example of use
```
require_once 'vendor/autoload.php';


use lexerom\cryptobillings\Payment;
use lexerom\cryptobillings\Item;
use lexerom\cryptobillings\AddressInfo;
use lexerom\cryptobillings\ShopInfo;

$payment = new Payment('YOU_API_KEY_HERE');

$item1 = new Item();
$item1->description = 'Item 1';
$item1->price = 2;
$item1->currency = 'EUR';
$item1->quantity = 1;


$item2 = new Item();
$item2->description = 'DHL Shipping';
$item2->price = 1;
$item2->currency = 'EUR';
$item2->quantity = 1;


$shipping = new AddressInfo();
$shipping->name = 'John Johnson';
$shipping->line1 = 'Railway station';
$shipping->line2 = 'Some street here';
$shipping->city = 'New Yourk';
$shipping->countryCode = 'US';
$shipping->postalCode = '123456';
$shipping->state = 'New York';
$shipping->phone = '+79876543210';
$shipping->type = 'shipping';

$billing = clone $shipping;
$billing->type = 'billing';

$shopInfo = new ShopInfo();
$shopInfo->customerEmail = 'youremail@example.com';
$shopInfo->shopName = 'Weed shop boom';

$response = $payment->createOrder('EUR', 3, 'DOPE', 'Test order', 'https://yoursite.com/success-url', 'https://yoursite.com/cancel-url', 'https://yoursite.com/notify-url', 'p1', 'p2', [$item1, $item2], $shipping, $billing);
```

`$response` looks like the following:
```
$response = [
    'result' => [
        'order_token' => 'ORDER_TOKEN',
        'payment_status' => 0, //0 - new
        'redirect_url' => 'REDIRECT_URL where you need to redirect your user to proceed',
        'amount_out' => 'SOME_AMOUNT_IN_REQUESTED_CRYPTOCURRENCY'                 
    ],
    'status' => 200,
    'error' => ''
];
```

notify_url - where notifications about status will go, success_url - user redirected here after successful payment, cancel_url - user reidrected here when he presses cancel or time has ended