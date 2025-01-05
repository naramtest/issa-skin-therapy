<?php

namespace App\Http\Controllers;

class CheckoutController extends Controller
{
    public function index()
    {
        return view("storefront.checkout.checkout");
    }

    public function success()
    {
        //TODO: success
        return view("storefront.checkout.success");
    }
}
