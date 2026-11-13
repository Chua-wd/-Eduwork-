<?php

// Shop settings for the cart and checkout.
return [

    // Orders from this subtotal ship for free; below it the fee is added.
    'free_shipping_from' => 200000,
    'shipping_fee' => 15000,

    // Bank transfer details shown on the payment page (example data, change before real use).
    'bank' => [
        'name' => 'BCA',
        'account' => '123 456 7890',
        'holder' => 'PT Navir Indonesia',
    ],

];
