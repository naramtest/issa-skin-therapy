<?php

namespace App\Http\Controllers;

class ShippingController extends Controller
{
    public function __construct()
    {
    }

    public function showTracking()
    {
        return view("storefront.shipping.tracking");
    }
}
