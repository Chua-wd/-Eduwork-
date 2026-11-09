<?php

namespace App\Http\Controllers;

class OrderController extends Controller
{
    // GET /cart -> halaman keranjang
    public function cart()
    {
        return view('cart');
    }

    // GET /checkout -> halaman checkout
    public function checkout()
    {
        return view('checkout');
    }
}
