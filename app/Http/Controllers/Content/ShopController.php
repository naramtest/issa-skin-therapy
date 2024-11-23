<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function index()
    {
        return view("storefront.shop");
    }
}
